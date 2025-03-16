<?php
declare(strict_types=1);

namespace AisearchClient;

class Action
{

    /** @var Controller */
    public $controller;
    /** @var Response */
    public $response;

    public function __construct($controller)
    {
        $this->controller = $controller;
    }


    public function request(string $url, string $method = 'GET', array $data = [])
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        $headers = [
            "User-Agent: Aisearch SDK v0.1",
            "Content-Type: application/json"
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Metoda göre ayarları düzenle
        switch (strtoupper($method)) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                if (!empty($data)) {
                    $jsonData = json_encode($data);
                    if ($jsonData === false) {
                        throw new \Exception("JSON encode error: " . json_last_error_msg());
                    }
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
                }
                break;
            case 'PUT':
            case 'DELETE':
            case 'PATCH':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
                if (!empty($data)) {
                    $jsonData = json_encode($data);
                    if ($jsonData === false) {
                        throw new \Exception("JSON encode error: " . json_last_error_msg());
                    }
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
                }
                break;
            default:
                // GET varsayılan metoddur; ek bir ayara gerek yok
                break;
        }

        // İsteği çalıştır
        $response = curl_exec($ch);

        // cURL hata kontrolü
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \Exception("cURL error: {$error}");
        }

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $decodedResponse = null;
        if (!empty($response)) {
            // JSON decode işlemi ve hata kontrolü
            $decodedResponse = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("JSON decode error: " . json_last_error_msg());
            }
        }

        // HTTP başarısızlığı durumunda exception fırlat
        if ($httpcode < 200 || $httpcode >= 300) {
            $errorMsg = "Request failed with HTTP status code {$httpcode}.";
            if (is_array($decodedResponse) && isset($decodedResponse['error'])) {
                $errorMsg .= " Error: " . $decodedResponse['error'];
            }
            throw new \Exception($errorMsg, $httpcode);
        }

        return new Response($decodedResponse, (int)$httpcode, "");
    }

}
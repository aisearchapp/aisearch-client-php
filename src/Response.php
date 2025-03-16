<?php
declare(strict_types=1);

namespace AisearchClient;

class Response
{

    public $result = null;
    public ?int $code = null;
    public $error = null;

    /**
     * @param null $result
     * @param null $code
     * @param null $error
     */
    public function __construct($result, $code, $error)
    {
        $this->result = $result;
        $this->code = $code;
        $this->error = $error;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->code >= 200 && $this->code <= 299;
    }

}
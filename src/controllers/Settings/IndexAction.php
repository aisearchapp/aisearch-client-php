<?php
declare(strict_types=1);

namespace AisearchClient\controllers\Settings;

use AisearchClient\Action;
use AisearchClient\models\SettingsCtaModel;
use AisearchClient\models\SettingsModel;
use AisearchClient\models\SettingsSubscriptionModel;

/**
 * Class IndexAction
 *
 * This action is responsible for fetching the settings configuration from the Aisearch API.
 * It constructs the request URL using the client token and processes the API response
 * into a SettingsModel instance.
 */
class IndexAction extends Action
{
    /**
     * Builds the full API request URL for retrieving settings.
     *
     * It converts the action parameters into an array, builds a query string from them,
     * and appends it to the base URL.
     *
     * @return string The complete API endpoint URL for the settings.
     */
    public function buildUrl(): string
    {
        // Convert parameters to an array and build query string
        $params = $this->paramsToArray();
        $queryString = http_build_query($params);
        // Return the full URL by appending the query string to the base URL
        return $this->controller->base() . "/settings?" . $queryString;
    }

    /**
     * Converts the required action parameters into an associative array.
     *
     * Only the client token is required in this case.
     *
     * @return array The array of parameters for the API request.
     */
    public function paramsToArray(): array
    {
        return [
            'client-token' => $this->controller->client_token,
        ];
    }

    /**
     * Executes the settings retrieval action.
     *
     * Sends an API request using the built URL, processes the response,
     * and if successful, creates a SettingsModel with the response data.
     * Otherwise, the model is set to null.
     *
     * @return $this Returns the current instance with the model populated if successful.
     */
    public function get()
    {
        // Send the API request using the built URL
        $this->response = $this->request($this->buildUrl());

        if ($this->response->code === 200) {
            // If the response code is 200, process the result into a SettingsModel
            $response = $this->response->result;
            $this->model = new SettingsModel(
                (bool) $response['status'],
                $response['language_id'],
                new SettingsCtaModel($response['cta']['typing']),
                $response['currencies'],
                new SettingsSubscriptionModel($response['subscription']['remove_branding'])
            );
        } else {
            // Otherwise, set the model to null
            $this->model = null;
        }

        return $this;
    }
}

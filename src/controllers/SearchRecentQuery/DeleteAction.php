<?php
declare(strict_types=1);

namespace AisearchClient\controllers\SearchRecentQuery;

use AisearchClient\Action;

/**
 * Class DeleteAction
 *
 * This action handles the deletion of a recent search query.
 * It builds the API request URL using the provided parameters,
 * sends a DELETE request to the API, and returns a boolean value
 * indicating whether the deletion was successful (HTTP 204).
 */
class DeleteAction extends Action
{
    /**
     * The search query to be deleted.
     *
     * @var string
     */
    public string $query;

    /**
     * The identifier for the user associated with the search query.
     *
     * @var string
     */
    public string $user_id;

    /**
     * Sets the search query to be deleted.
     *
     * @param string $query The search query.
     * @return DeleteAction
     */
    public function setQuery(string $query): DeleteAction
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Sets the user ID associated with the search query.
     *
     * @param string $user_id The user identifier.
     * @return DeleteAction
     */
    public function setUserId(string $user_id): DeleteAction
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * Builds the full API request URL for deleting a recent search query.
     *
     * This method converts the action parameters to an associative array,
     * builds a query string from these parameters, and appends it to the base URL.
     *
     * @return string The complete API endpoint URL.
     */
    public function buildUrl(): string
    {
        $params = $this->paramsToArray();
        $queryString = http_build_query($params);
        return $this->controller->base() . "/search/query/recent?" . $queryString;
    }

    /**
     * Converts the current action parameters into an associative array.
     *
     * Only the required parameters (client-token and user_id) are included.
     *
     * @return array The array of parameters for the API request.
     */
    public function paramsToArray(): array
    {
        return [
            'client-token' => $this->controller->client_token,
            'user_id'      => $this->user_id,
        ];
    }

    /**
     * Executes the delete action.
     *
     * Sends a DELETE request to the API using the built URL and the query parameter.
     * It returns true if the response code is 204 (No Content), indicating a successful deletion.
     *
     * @return bool True if the deletion was successful; false otherwise.
     */
    public function delete(): bool
    {
        $this->response = $this->request($this->buildUrl(), 'DELETE', ['query' => $this->query]);
        return $this->response->code === 204;
    }
}

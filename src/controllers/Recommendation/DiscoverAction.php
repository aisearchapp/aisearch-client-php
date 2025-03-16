<?php
declare(strict_types=1);

namespace AisearchClient\controllers\Recommendation;

use AisearchClient\Action;
use AisearchClient\models\RecommendationDiscoverModel;

/**
 * Class DiscoverAction
 *
 * This action handles the retrieval of discovery-based recommendations.
 * It builds an API request using various parameters (user, limit, after cursor, segments)
 * and processes the API response into a RecommendationDiscoverModel.
 */
class DiscoverAction extends Action
{
    /**
     * User identifier for the recommendation request.
     *
     * @var string
     */
    public string $user_id;

    /**
     * Maximum number of recommendations to retrieve.
     *
     * @var int
     */
    public int $limit = 30;

    /**
     * Pagination cursor for fetching the next set of recommendations.
     *
     * @var string
     */
    public string $after;

    /**
     * Segments to refine the recommendation query.
     *
     * @var string
     */
    public string $segments;

    /**
     * Negative segments to exclude certain recommendations.
     *
     * @var string
     */
    public string $negative_segments;

    /**
     * The model that will store the recommendation discover results.
     *
     * @var RecommendationDiscoverModel|null
     */
    public ?RecommendationDiscoverModel $model;

    /**
     * Gets the user ID.
     *
     * @return string The current user ID.
     */
    public function getUserId(): string
    {
        return $this->user_id;
    }

    /**
     * Sets the user ID.
     *
     * @param string $user_id The user ID to set.
     * @return DiscoverAction
     */
    public function setUserId(string $user_id): DiscoverAction
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * Sets the segments parameter.
     *
     * @param string $segments The segments to filter recommendations.
     * @return DiscoverAction
     */
    public function setSegments(string $segments): DiscoverAction
    {
        $this->segments = $segments;
        return $this;
    }

    /**
     * Sets the negative segments parameter.
     *
     * @param string $negative_segments The segments to exclude from recommendations.
     * @return DiscoverAction
     */
    public function setNegativeSegments(string $negative_segments): DiscoverAction
    {
        $this->negative_segments = $negative_segments;
        return $this;
    }

    /**
     * Gets the limit for recommendation results.
     *
     * @return int The current limit.
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * Sets the limit for recommendation results.
     *
     * @param int $limit The maximum number of recommendations to retrieve.
     * @return DiscoverAction
     */
    public function setLimit(int $limit): DiscoverAction
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Gets the current "after" pagination cursor.
     *
     * @return string The pagination cursor.
     */
    public function getAfter(): string
    {
        return $this->after;
    }

    /**
     * Sets the "after" pagination cursor.
     *
     * @param string $after The pagination cursor to set.
     * @return DiscoverAction
     */
    public function setAfter(string $after): DiscoverAction
    {
        $this->after = $after;
        return $this;
    }

    /**
     * Builds the full API request URL for the discover recommendations.
     *
     * It converts the action parameters to an array and appends them as a query string.
     *
     * @return string The complete API endpoint URL.
     */
    public function buildUrl(): string
    {
        $params = $this->paramsToArray();
        $queryString = http_build_query($params);
        return $this->controller->base() . "/recommendation/discover?" . $queryString;
    }

    /**
     * Executes the discover recommendation action.
     *
     * Sends the API request and processes the response.
     * If the HTTP response code is 200, the response is used to create a RecommendationDiscoverModel.
     * Otherwise, the model is set to null.
     *
     * @return $this Returns the current instance with the model populated if successful.
     */
    public function get()
    {
        $this->response = $this->request($this->after ?: $this->buildUrl());

        if ($this->response->code === 200) {
            $response = $this->response->result;
            $this->model = new RecommendationDiscoverModel(
                $response['attributes'],
                $response['attribute_parents'],
                $response['products'],
                $response['count'],
                $response['page']
            );
        } else {
            $this->model = null;
        }

        return $this;
    }

    /**
     * Converts the current action parameters into an associative array.
     *
     * Only non-empty parameters are included.
     *
     * @return array The array of parameters for the API request.
     */
    public function paramsToArray(): array
    {
        $params = [
            'client-token' => $this->controller->client_token,
            'user_id'      => $this->user_id,
            'limit'        => $this->limit,
        ];

        if (!empty($this->after)) {
            $params['after'] = $this->after;
        }
        if (!empty($this->segments)) {
            $params['segments'] = $this->segments;
        }
        if (!empty($this->negative_segments)) {
            $params['negative_segments'] = $this->negative_segments;
        }
        return $params;
    }

    /**
     * Determines whether there is a next page of results.
     *
     * This method checks the current API response to see if the pagination data
     * indicates the existence of a next page. If the 'next' value in the 'page' array is greater than 0,
     * it means more results are available.
     *
     * @return bool True if a next page exists; false otherwise.
     */
    public function hasNextPage(): bool
    {
        return $this->response->result['page']['has_next'];
    }


    /**
     * Retrieves the next page of search results.
     *
     * This method uses the `hasNextPage()` function to check if there is a subsequent page.
     * If a next page is available, it updates the current page number to the next page,
     * re-executes the search query by calling the `get()` method, and returns the updated instance.
     * If no next page exists, the method returns null.
     *
     * @return $this|null The current instance with updated results if a next page is available; otherwise, null.
     */
    public function next()
    {
        if ($this->hasNextPage()) {
            $this->setAfter($this->response->result['page']['after']);
            $this->get();
            return $this;
        }
        return null;
    }

    /**
     * Resets the pagination and retrieves the first set of recommendations.
     *
     * @return $this Returns the current instance with the initial recommendations.
     */
    public function first()
    {
        $this->setAfter("");
        $this->get();
        return $this;
    }
}

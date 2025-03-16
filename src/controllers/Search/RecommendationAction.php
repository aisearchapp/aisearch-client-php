<?php
declare(strict_types=1);

namespace AisearchClient\controllers\Search;

use AisearchClient\Action;
use AisearchClient\models\SearchRecommendationCtaModel;
use AisearchClient\models\SearchRecommendationInterestModel;
use AisearchClient\models\SearchRecommendationModel;
use AisearchClient\models\SearchRecommendationPopularModel;

/**
 * Class RecommendationAction
 *
 * This action handles the retrieval of search recommendations.
 * It builds the API request URL using the provided parameters (such as user ID, product limit, and segments),
 * sends the request, and processes the response into a SearchRecommendationModel.
 */
class RecommendationAction extends Action
{
    /**
     * The identifier for the user making the request.
     *
     * @var string
     */
    public string $user_id;

    /**
     * The maximum number of products to be included in the recommendation.
     *
     * @var int
     */
    public int $product_limit = 5;

    /**
     * Segments used to refine or target the recommendation.
     *
     * @var string
     */
    public string $segments;

    /**
     * Negative segments used to exclude certain recommendations.
     *
     * @var string
     */
    public string $negative_segments;

    /**
     * The recommendation model containing the API response data.
     *
     * @var SearchRecommendationModel|null
     */
    public ?SearchRecommendationModel $model;

    /**
     * Retrieves the current user ID.
     *
     * @return string The user identifier.
     */
    public function getUserId(): string
    {
        return $this->user_id;
    }

    /**
     * Sets the user ID.
     *
     * @param string $user_id The user identifier.
     * @return RecommendationAction
     */
    public function setUserId(string $user_id): RecommendationAction
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * Sets the segments parameter.
     *
     * @param string $segments The segments used for filtering recommendations.
     * @return RecommendationAction
     */
    public function setSegments(string $segments): RecommendationAction
    {
        $this->segments = $segments;
        return $this;
    }

    /**
     * Sets the negative segments parameter.
     *
     * @param string $negative_segments The segments used for excluding recommendations.
     * @return RecommendationAction
     */
    public function setNegativeSegments(string $negative_segments): RecommendationAction
    {
        $this->negative_segments = $negative_segments;
        return $this;
    }

    /**
     * Retrieves the product limit.
     *
     * @return int The maximum number of products to retrieve.
     */
    public function getProductLimit(): int
    {
        return $this->product_limit;
    }

    /**
     * Sets the product limit.
     *
     * @param int $product_limit The maximum number of products.
     * @return RecommendationAction
     */
    public function setProductLimit(int $product_limit): RecommendationAction
    {
        $this->product_limit = $product_limit;
        return $this;
    }

    /**
     * Builds the full API request URL for the search recommendation.
     *
     * It converts the action parameters to an array, builds a query string,
     * and appends it to the base URL.
     *
     * @return string The complete API endpoint URL.
     */
    public function buildUrl(): string
    {
        // Convert parameters to an associative array
        $params = $this->paramsToArray();
        // Build query string from parameters
        $queryString = http_build_query($params);
        // Return the full URL using the base URL from the controller
        return $this->controller->base() . "/search/recommendation?" . $queryString;
    }

    /**
     * Executes the recommendation action.
     *
     * Sends the API request using the constructed URL and processes the response.
     * If the response code is 200, it instantiates a SearchRecommendationModel using
     * various parts of the response (attribute parents, attributes, interests, popular items,
     * call-to-action, and recent searches).
     * Otherwise, it sets the model to null.
     *
     * @return $this Returns the current instance.
     */
    public function get()
    {
        $this->response = $this->request($this->buildUrl());

        if ($this->response->code === 200) {
            $response = $this->response->result;
            $this->model = new SearchRecommendationModel(
                $response['attribute_parents'] ?? [],
                $response['attributes'] ?? [],
                new SearchRecommendationInterestModel(
                    $response['interests']['clicks'],
                    $response['interests']['products']
                ),
                new SearchRecommendationPopularModel(
                    $response['popular']['searches'],
                    $response['popular']['categories'],
                    $response['popular']['products']
                ),
                new SearchRecommendationCtaModel(
                    $response['cta']['typing']
                ),
                $response['recent']
            );
        } else {
            $this->model = null;
        }

        return $this;
    }

    /**
     * Converts the action parameters into an associative array.
     *
     * Only non-empty parameters are included.
     *
     * @return array The parameters for the API request.
     */
    public function paramsToArray(): array
    {
        $params = [
            'user_id'       => $this->user_id,
            'client-token'  => $this->controller->client_token,
            'product-limit' => $this->product_limit,
        ];
        if (!empty($this->segments)) {
            $params['segments'] = $this->segments;
        }
        if (!empty($this->negative_segments)) {
            $params['negative_segments'] = $this->negative_segments;
        }
        return $params;
    }
}

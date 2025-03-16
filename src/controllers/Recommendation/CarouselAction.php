<?php
declare(strict_types=1);

namespace AisearchClient\controllers\Recommendation;

use AisearchClient\Action;
use AisearchClient\models\RecommendationCarouselModel;

/**
 * Class CarouselAction
 *
 * This action handles retrieving carousel-style recommendations.
 * It builds the API request URL using various parameters (user, category, brand, segments)
 * and processes the response to create a RecommendationCarouselModel.
 */
class CarouselAction extends Action
{
    /**
     * User identifier for the recommendation request.
     *
     * @var string
     */
    public string $user_id;

    /**
     * Category identifier used for filtering recommendations.
     *
     * @var int
     */
    public int $category_id;

    /**
     * Remote key associated with the category.
     *
     * @var string
     */
    public string $category_remote_key;

    /**
     * Brand identifier used for filtering recommendations.
     *
     * @var int
     */
    public int $brand_id;

    /**
     * Remote key associated with the brand.
     *
     * @var string
     */
    public string $brand_remote_key;

    /**
     * Name of the brand.
     *
     * @var string
     */
    public string $brand_name;

    /**
     * Segments parameter to refine the recommendation query.
     *
     * @var string
     */
    public string $segments;

    /**
     * Negative segments parameter to exclude certain recommendations.
     *
     * @var string
     */
    public string $negative_segments;

    /**
     * The recommendation carousel model that will store the API response data.
     *
     * @var RecommendationCarouselModel|null
     */
    public ?RecommendationCarouselModel $model;

    // ***********************
    // Getter and Setter Methods
    // ***********************

    public function getUserId(): string
    {
        return $this->user_id;
    }

    public function setUserId(string $user_id): CarouselAction
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function getSegments(): string
    {
        return $this->segments;
    }

    public function setSegments(string $segments): CarouselAction
    {
        $this->segments = $segments;
        return $this;
    }

    public function getNegativeSegments(): string
    {
        return $this->negative_segments;
    }

    public function setNegativeSegments(string $negative_segments): CarouselAction
    {
        $this->negative_segments = $negative_segments;
        return $this;
    }

    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    public function setCategoryId(int $category_id): CarouselAction
    {
        $this->category_id = $category_id;
        return $this;
    }

    public function getCategoryRemoteKey(): string
    {
        return $this->category_remote_key;
    }

    public function setCategoryRemoteKey(string $category_remote_key): CarouselAction
    {
        $this->category_remote_key = $category_remote_key;
        return $this;
    }

    public function getBrandId(): int
    {
        return $this->brand_id;
    }

    public function setBrandId(int $brand_id): CarouselAction
    {
        $this->brand_id = $brand_id;
        return $this;
    }

    public function getBrandRemoteKey(): string
    {
        return $this->brand_remote_key;
    }

    public function setBrandRemoteKey(string $brand_remote_key): CarouselAction
    {
        $this->brand_remote_key = $brand_remote_key;
        return $this;
    }

    public function getBrandName(): string
    {
        return $this->brand_name;
    }

    public function setBrandName(string $brand_name): CarouselAction
    {
        $this->brand_name = $brand_name;
        return $this;
    }

    public function getModel(): ?RecommendationCarouselModel
    {
        return $this->model;
    }

    public function setModel(?RecommendationCarouselModel $model): CarouselAction
    {
        $this->model = $model;
        return $this;
    }

    // ***********************
    // Core Methods
    // ***********************

    /**
     * Builds the API request URL for the recommendation carousel.
     *
     * This method converts action parameters to an array,
     * builds a query string from them, and appends it to the base URL.
     *
     * @return string The full API endpoint URL.
     */
    public function buildUrl(): string
    {
        $params = $this->paramsToArray();
        $queryString = http_build_query($params);
        return $this->controller->base() . "/recommendation/carousel?" . $queryString;
    }

    /**
     * Executes the recommendation carousel action.
     *
     * Sends the API request using the built URL, then processes the response.
     * If the response is successful (HTTP 200), a RecommendationCarouselModel is instantiated.
     *
     * @return $this The current action instance with the model populated if successful.
     */
    public function get()
    {
        $this->response = $this->request($this->buildUrl());

        if ($this->response->code === 200) {
            $response = $this->response->result;
            $this->model = new RecommendationCarouselModel(
                $response['attributes'],
                $response['attribute_parents'],
                $response['products'],
                $response['personalized']
            );
        } else {
            $this->model = null;
        }

        return $this;
    }

    /**
     * Converts the current action parameters into an associative array.
     *
     * Only non-empty parameters are included in the array.
     *
     * @return array The parameters for the API request.
     */
    public function paramsToArray(): array
    {
        $params = [
            'client-token' => $this->controller->client_token,
            'user_id'      => $this->user_id,
        ];

        if (!empty($this->segments)) {
            $params['segments'] = $this->segments;
        }
        if (!empty($this->negative_segments)) {
            $params['negative_segments'] = $this->negative_segments;
        }
        if (!empty($this->category_id)) {
            $params['category_id'] = $this->category_id;
        }
        if (!empty($this->category_remote_key)) {
            $params['category_remote_key'] = $this->category_remote_key;
        }
        if (!empty($this->brand_id)) {
            $params['brand_id'] = $this->brand_id;
        }
        if (!empty($this->brand_remote_key)) {
            $params['brand_remote_key'] = $this->brand_remote_key;
        }
        if (!empty($this->brand_name)) {
            $params['brand_name'] = $this->brand_name;
        }

        return $params;
    }
}

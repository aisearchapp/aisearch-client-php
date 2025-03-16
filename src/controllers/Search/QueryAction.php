<?php
declare(strict_types=1);

namespace AisearchClient\controllers\Search;

use AisearchClient\Action;
use AisearchClient\models\SearchQueryModel;

/**
 * Class QueryAction
 *
 * This action class handles search query requests to the Aisearch API.
 * It builds the request URL based on various search parameters and sends
 * the request to fetch search results. The response is then processed and
 * converted into a SearchQueryModel instance.
 */
class QueryAction extends Action
{
    public string $user_id;
    public string $query;
    public int $limit = 30;
    public int $page = 1;
    public string $sort = "";
    public array $attributes = [];
    public float $min_price = 0.0;
    public float $max_price = 0.0;
    public string $segments;
    public string $negative_segments;
    public ?SearchQueryModel $model;

    // ***********************
    // Getter and Setter Methods
    // ***********************

    public function getUserId(): string
    {
        return $this->user_id;
    }

    public function setUserId(string $user_id): QueryAction
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function setSegments(string $segments): QueryAction
    {
        $this->segments = $segments;
        return $this;
    }

    public function setNegativeSegments(string $negative_segments): QueryAction
    {
        $this->negative_segments = $negative_segments;
        return $this;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function setQuery(string $query): QueryAction
    {
        $this->query = $query;
        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): QueryAction
    {
        $this->limit = $limit;
        return $this;
    }

    public function setFilterMinPrice(float $min_price): QueryAction
    {
        $this->min_price = $min_price;
        return $this;
    }

    public function setFilterMaxPrice(float $max_price): QueryAction
    {
        $this->max_price = $max_price;
        return $this;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): QueryAction
    {
        $this->page = $page;
        return $this;
    }

    public function getSort(): string
    {
        return $this->sort;
    }

    public function setSort(string $sort): QueryAction
    {
        $this->sort = $sort;
        return $this;
    }

    // Constants for expand options used in the API request.
    public const EXPAND_PRODUCT = 'product';
    public const EXPAND_FILTER = 'filter';
    public const EXPAND_POPULAR_CATEGORIES = 'popularCategories';
    public const EXPAND_RECOMMENDATION = 'recommendation';

    // Constants for sort options.
    public const SORT_DEFAULT = "";
    public const SORT_PRICE_ASC = "price";
    public const SORT_PRICE_DESC = "-price";
    public const SORT_NAME_ASC = "name";
    public const SORT_NAME_DESC = "-name";
    public const SORT_CREATED_AT_ASC = "created_at";
    public const SORT_CREATED_AT_DESC = "-created_at";

    /**
     * Expand options for the API request.
     *
     * This public property can be directly manipulated if needed.
     *
     * @var array
     */
    public $expand = [
        self::EXPAND_PRODUCT,
        self::EXPAND_FILTER,
        self::EXPAND_POPULAR_CATEGORIES,
        self::EXPAND_RECOMMENDATION
    ];

    // ***********************
    // Core Methods
    // ***********************

    public function buildUrl(): string
    {
        $params = $this->paramsToArray();
        $queryString = http_build_query($params);
        return $this->controller->base() . "/search/query?" . $queryString;
    }

    /**
     * Executes the search query.
     *
     * Sends the API request using the constructed URL. If the response code is 200,
     * it processes the response data into a SearchQueryModel instance.
     * Otherwise, it sets the model to null.
     *
     * @return $this The current instance, allowing method chaining.
     */
    public function get()
    {
        $this->response = $this->request($this->buildUrl());

        if ($this->response->code === 200) {
            $response = $this->response->result;
            $this->model = new SearchQueryModel(
                $response['status'],
                $response['count'],
                $response['products'],
                $response['page'],
                $response['attribute_parents'],
                $response['attributes'],
                $response['recent'],
                $response['query'],
                $response['filter'],
                $response['popularCategories'],
                $response['recommendation']
            );
        } else {
            $this->model = null;
        }

        return $this;
    }

    /**
     * Adds a filter attribute to the search query.
     *
     * Updates the internal attributes array by adding a child attribute ID
     * under the given parent attribute ID.
     *
     * @param int $parent_id The ID of the parent attribute.
     * @param int $child_id  The ID of the child attribute to add.
     * @return QueryAction
     */
    public function addFilterAttribute(int $parent_id, int $child_id): QueryAction
    {
        $attributes = $this->attributes;
        if (!isset($attributes[$parent_id])) {
            $attributes[$parent_id] = [];
        }
        if (!in_array($child_id, $attributes[$parent_id])) {
            $attributes[$parent_id][] = $child_id;
        }
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * Removes a specific filter attribute from the search query.
     *
     * Deletes the child attribute ID from the specified parent attribute's list.
     * If the parent attribute no longer has any child attributes, it removes the parent key.
     *
     * @param int $parent_id The ID of the parent attribute.
     * @param int $child_id  The ID of the child attribute to remove.
     * @return QueryAction
     */
    public function removeFilterAttribute(int $parent_id, int $child_id): QueryAction
    {
        $attributes = $this->attributes;
        if (isset($attributes[$parent_id])) {
            $key = array_search($child_id, $attributes[$parent_id]);
            if ($key !== false) {
                unset($attributes[$parent_id][$key]);
                $attributes[$parent_id] = array_values($attributes[$parent_id]);
            }
            if (empty($attributes[$parent_id])) {
                unset($attributes[$parent_id]);
            }
        }
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * Removes all filter attributes.
     *
     * If a parent attribute ID is provided, it removes all child attributes for that parent.
     * If no parent attribute is specified, it clears all filter attributes.
     *
     * @param int|null $parent_id The parent attribute ID to clear, or null to clear all.
     * @return QueryAction
     */
    public function removeAllFilterAttributes(?int $parent_id = null): QueryAction
    {
        if ($parent_id !== null) {
            $attributes = $this->attributes;
            unset($attributes[$parent_id]);
            $this->attributes = $attributes;
        } else {
            $this->attributes = [];
        }
        return $this;
    }

    /**
     * Converts the current search query parameters into an associative array.
     *
     * Processes filter attributes into a formatted string and combines all parameters
     * including query, user ID, expand options, client token, pagination, sort, and price filters.
     *
     * @return array An associative array of search query parameters.
     */
    public function paramsToArray(): array
    {
        $attributes = [];

        foreach ($this->attributes as $parentId => $children) {
            $attributes[] = $parentId . ':' . implode(",", $children);
        }
        $params = [
            'query'         => $this->query,
            'user_id'       => $this->user_id,
            'expand'        => implode(",", $this->expand),
            'client-token'  => $this->controller->client_token,
            'limit'         => $this->limit,
            'page'          => $this->page,
            'sort'          => $this->sort,
            'attributes'    => implode("|", $attributes),
            'min_price'     => $this->min_price,
            'max_price'     => $this->max_price,
        ];
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
        return $this->response->result['page']['next'] > 0;
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
            $this->page = $this->response->result['page']['next'];
            $this->get();
            return $this;
        }
        return null;
    }

    /**
     * Resets the search query to the first page and executes the search.
     *
     * @return $this The current instance after resetting to the first page.
     */
    public function first()
    {
        $this->page = 1;
        $this->get();
        return $this;
    }


}

<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class SearchQueryModel
 *
 * This model represents the result of a search query.
 * It encapsulates various pieces of data returned from the API including:
 * - The overall status and count of results.
 * - An array of product models.
 * - Pagination details (if available) as a PageModel instance.
 * - Arrays of attribute parent and child models.
 * - Recent search queries.
 * - The original search query string.
 * - A filter model containing applied filter details.
 * - An array of popular category models.
 * - A recommendation model (if available).
 *
 * The constructor processes raw arrays from the API response and converts them into their respective model instances.
 */
class SearchQueryModel
{
    /**
     * The status of the search query response.
     *
     * @var string
     */
    public string $status;

    /**
     * The total number of results returned.
     *
     * @var int
     */
    public int $count;

    /**
     * An array of product models.
     *
     * @var ProductModel[]
     */
    public array $products;

    /**
     * Pagination details, if available.
     *
     * @var PageModel|null
     */
    public ?PageModel $page;

    /**
     * An array of parent attribute models.
     *
     * @var AttributeParentModel[]
     */
    public array $attribute_parents = [];

    /**
     * An array of child attribute models.
     *
     * @var AttributeChildModel[]
     */
    public array $attributes;

    /**
     * An array of recent search queries.
     *
     * @var array
     */
    public array $recent;

    /**
     * The search query string.
     *
     * @var string
     */
    public string $query;

    /**
     * A filter model containing filter details (if any).
     *
     * @var FilterModel|null
     */
    public ?FilterModel $filter;

    /**
     * An array of popular category models.
     *
     * @var PopularCategoryModel[]
     */
    public array $popular_categories;

    /**
     * A recommendation model providing additional recommendations (if any).
     *
     * @var RecommendationModel|null
     */
    public ?RecommendationModel $recommendation;

    /**
     * Constructor for SearchQueryModel.
     *
     * Processes raw API response data and converts them into structured model instances.
     *
     * @param string $status            The response status.
     * @param int $count                The total number of results.
     * @param array $products           Raw array of products data.
     * @param mixed $page               Raw pagination data (or null if not provided).
     * @param array $attribute_parents  Raw array of parent attribute data.
     * @param array $attributes         Raw array of child attribute data.
     * @param array $recent             Array of recent search queries.
     * @param string $query             The search query string.
     * @param mixed $filter             Raw filter data (or null if not provided).
     * @param array $popular_categories Raw array of popular category data.
     * @param mixed $recommendation     Raw recommendation data (or null if not provided).
     */
    public function __construct(
        string $status,
        int $count,
        array $products,
               $page,
        array $attribute_parents,
        array $attributes,
        array $recent,
        string $query,
               $filter,
        array $popular_categories,
               $recommendation
    ) {
        // Process raw popular categories data into PopularCategoryModel instances.
        foreach ($popular_categories as $key => $popular_category) {
            $popular_categories[$key] = new PopularCategoryModel(
                $popular_category['id'],
                $popular_category['name'],
                $popular_category['image_url'],
                $popular_category['url'],
                $popular_category['custom'],
                $popular_category['position'],
                $popular_category['created_at'],
                $popular_category['updated_at']
            );
        }

        // Process raw product data.
        // For each product, convert the 'variants' array into ProductVariantModel instances,
        // then convert the entire product data into a ProductModel instance.
        foreach ($products as $key => $product) {
            foreach ($product['variants'] as $variantKey => $variant) {
                $product['variants'][$variantKey] = new ProductVariantModel(
                    (string)$variant['name'],
                    (int)$variant['stock'],
                    (float)$variant['buying_price'],
                    (float)$variant['price'],
                    (string)$variant['sku'],
                    (string)$variant['master_key'],
                    $variant['custom'],
                    $variant['attributes']
                );
            }
            $products[$key] = new ProductModel(
                (int)$product['id'],
                (string)$product['name'],
                (array)$product['images'],
                (string)$product['url'],
                (int)$product['stock'],
                (bool)$product['is_new'],
                (float)$product['buying_price'],
                (float)$product['price'],
                (string)$product['currency_code'],
                (int)$product['category_id'],
                (int)$product['brand_id'],
                (string)$product['sku'],
                (string)$product['master_key'],
                (string)$product['barcode'],
                $product['custom'],
                $product['attributes'],
                $product['variants'],
                (string)$product['brand']
            );
        }

        // Process raw parent attribute data into AttributeParentModel instances.
        foreach ($attribute_parents as $key => $attribute_parent) {
            $attribute_parents[$key] = new AttributeParentModel(
                (int)$attribute_parent['id'],
                (int)$attribute_parent['group_id'],
                (int)$attribute_parent['position'],
                (string)$attribute_parent['name'],
                (string)$attribute_parent['regular_name'],
                (string)$attribute_parent['filter_label'],
                (string)$attribute_parent['filter_type'],
                (string)$attribute_parent['remote_key'],
                (bool)$attribute_parent['show_in_full_search'],
                (bool)$attribute_parent['show_in_recommendation'],
                (string)$attribute_parent['recommendation_title'],
                (bool)$attribute_parent['is_option'],
                (string)$attribute_parent['created_at'],
                (string)$attribute_parent['updated_at']
            );
        }

        // Process raw child attribute data into AttributeChildModel instances.
        foreach ($attributes as $key => $attribute) {
            $attributes[$key] = new AttributeChildModel(
                (int)$attribute['id'],
                (int)$attribute['parent_id'],
                (int)$attribute['group_id'],
                (int)$attribute['position'],
                (string)$attribute['name'],
                (string)$attribute['regular_name'],
                (string)$attribute['filter_label'],
                (string)$attribute['color_code'],
                (string)$attribute['remote_key'],
                (string)$attribute['created_at'],
                (string)$attribute['updated_at']
            );
        }

        // Set class properties with processed data.
        $this->status = $status;
        $this->count = $count;
        $this->products = $products;

        // Process pagination data if provided.
        if ($page) {
            $this->page = new PageModel($page['count'], $page['next']);
        }

        $this->attribute_parents = $attribute_parents;
        $this->attributes = $attributes;
        $this->recent = $recent;
        $this->query = $query;

        // Process filter data if provided.
        if ($filter) {
            $this->filter = new FilterModel($filter['selected'], $filter['attributes'], $filter['price']);
        }

        $this->popular_categories = $popular_categories;

        // Process recommendation data if provided.
        if ($recommendation) {
            $this->recommendation = new RecommendationModel($recommendation['relating'], $recommendation['autocomplete']);
        }
    }
}

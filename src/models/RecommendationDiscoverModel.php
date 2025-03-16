<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class RecommendationDiscoverModel
 *
 * This model encapsulates data for discovery-based recommendations.
 * It converts raw API response data into structured model instances for:
 * - Child attributes (using AttributeChildModel)
 * - Parent attributes (using AttributeParentModel)
 * - Products (using ProductModel, which in turn processes variants into ProductVariantModel)
 * Additionally, it stores the total count of recommendations and pagination details.
 */
class RecommendationDiscoverModel
{
    /**
     * An array of child attribute models.
     *
     * @var AttributeChildModel[]
     */
    public $attributes;

    /**
     * An array of parent attribute models.
     *
     * @var AttributeParentModel[]
     */
    public $attribute_parents;

    /**
     * An array of product models.
     *
     * @var ProductModel[]
     */
    public $products;

    /**
     * The total count of recommended products.
     *
     * @var int
     */
    public int $count;

    /**
     * The pagination details for the recommendations.
     *
     * @var RecommendationDiscoverPageModel
     */
    public RecommendationDiscoverPageModel $page;

    /**
     * Constructor for RecommendationDiscoverModel.
     *
     * This constructor processes raw arrays for attributes, parent attributes, and products.
     * For products, it converts each product's raw variant data into ProductVariantModel instances,
     * then creates a ProductModel instance. It also converts raw attribute parent and child data
     * into their respective model instances. Finally, it initializes the page property with
     * a new RecommendationDiscoverPageModel instance.
     *
     * @param array $attributes Raw data for child attributes.
     * @param array $attribute_parents Raw data for parent attributes.
     * @param array $products Raw data for products.
     * @param int $count Total number of recommended products.
     * @param array $page Raw pagination data (e.g., limit, count, has_next, after).
     */
    public function __construct($attributes, $attribute_parents, $products, $count, $page)
    {
        // Process each product:
        // Convert raw variant data into ProductVariantModel instances,
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

        // Process parent attributes:
        // Convert each raw parent attribute into an AttributeParentModel instance.
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

        // Process child attributes:
        // Convert each raw child attribute into an AttributeChildModel instance.
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

        // Assign processed models to class properties.
        $this->attributes = $attributes;
        $this->attribute_parents = $attribute_parents;
        $this->products = $products;
        $this->count = $count;
        // Instantiate the pagination model from the raw page data.
        $this->page = new RecommendationDiscoverPageModel(
            (int)$page['limit'],
            (int)$page['count'],
            (bool)$page['has_next'],
            (string)$page['after']
        );
    }
}

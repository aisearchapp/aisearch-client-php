<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class RecommendationCarouselModel
 *
 * This model encapsulates data for a recommendation carousel.
 * It converts raw API response data into structured model instances for:
 * - Child attributes (using AttributeChildModel)
 * - Parent attributes (using AttributeParentModel)
 * - Products (using ProductModel and ProductVariantModel for variants)
 *
 * Additionally, it holds a flag indicating whether the recommendations are personalized.
 */
class RecommendationCarouselModel
{
    /**
     * An array of child attribute models.
     *
     * @var AttributeChildModel[]
     */
    public array $attributes;

    /**
     * An array of parent attribute models.
     *
     * @var AttributeParentModel[]
     */
    public array $attribute_parents;

    /**
     * An array of product models.
     *
     * @var ProductModel[]
     */
    public array $products;

    /**
     * Indicates whether the recommendations are personalized.
     *
     * @var bool
     */
    public bool $personalized;

    /**
     * Constructor for RecommendationCarouselModel.
     *
     * This constructor processes the raw arrays for attributes, attribute parents, and products.
     * For each product, it converts the raw variant data into ProductVariantModel instances,
     * then creates a ProductModel instance for the product.
     * It similarly converts raw parent and child attribute data into their respective model instances.
     *
     * @param array $attributes Raw data for child attributes.
     * @param array $attribute_parents Raw data for parent attributes.
     * @param array $products Raw data for products.
     * @param bool $personalized Whether the recommendations are personalized.
     */
    public function __construct(array $attributes, array $attribute_parents, array $products, bool $personalized)
    {
        // Process products: convert raw variant arrays into ProductVariantModel instances,
        // then convert the entire product array into a ProductModel instance.
        foreach ($products as $key => $product) {
            // Process each product's variants.
            foreach ($product['variants'] as $variantKey => $variant) {
                $product['variants'][$variantKey] = new ProductVariantModel(
                    $variant['name'],
                    $variant['stock'],
                    (float)$variant['buying_price'],
                    (float)$variant['price'],
                    $variant['sku'],
                    $variant['master_key'],
                    $variant['custom'],
                    $variant['attributes']
                );
            }
            // Convert product data into a ProductModel instance.
            $products[$key] = new ProductModel(
                $product['id'],
                $product['name'],
                $product['images'],
                $product['url'],
                $product['stock'],
                (bool)$product['is_new'],
                (float)$product['buying_price'],
                (float)$product['price'],
                $product['currency_code'],
                $product['category_id'],
                $product['brand_id'],
                $product['sku'],
                $product['master_key'],
                $product['barcode'],
                $product['custom'],
                $product['attributes'],
                $product['variants'],
                $product['brand']
            );
        }

        // Process attribute parents: convert each raw parent attribute into an AttributeParentModel instance.
        foreach ($attribute_parents as $key => $attribute_parent) {
            $attribute_parents[$key] = new AttributeParentModel(
                $attribute_parent['id'],
                $attribute_parent['group_id'],
                $attribute_parent['position'],
                $attribute_parent['name'],
                $attribute_parent['regular_name'],
                $attribute_parent['filter_label'],
                $attribute_parent['filter_type'],
                $attribute_parent['remote_key'],
                (bool)$attribute_parent['show_in_full_search'],
                (bool)$attribute_parent['show_in_recommendation'],
                $attribute_parent['recommendation_title'],
                (bool)$attribute_parent['is_option'],
                $attribute_parent['created_at'],
                $attribute_parent['updated_at'],
            );
        }

        // Process child attributes: convert each raw attribute into an AttributeChildModel instance.
        foreach ($attributes as $key => $attribute) {
            $attributes[$key] = new AttributeChildModel(
                $attribute['id'],
                $attribute['parent_id'],
                $attribute['group_id'],
                $attribute['position'],
                $attribute['name'],
                $attribute['regular_name'],
                $attribute['filter_label'],
                $attribute['color_code'],
                $attribute['remote_key'],
                $attribute['created_at'],
                $attribute['updated_at'],
            );
        }

        // Assign the processed data and personalized flag to class properties.
        $this->attributes = $attributes;
        $this->attribute_parents = $attribute_parents;
        $this->products = $products;
        $this->personalized = $personalized;
    }
}

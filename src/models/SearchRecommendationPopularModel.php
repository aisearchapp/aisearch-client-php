<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class SearchRecommendationPopularModel
 *
 * This model encapsulates popular recommendation data returned from the API.
 * It holds:
 * - An array of search terms (searches) that are popular.
 * - An array of popular category models.
 * - An array of product models representing popular products.
 *
 * The constructor processes raw arrays for categories and products, converting them into their
 * respective model instances.
 */
class SearchRecommendationPopularModel
{
    /**
     * An array of popular search terms.
     *
     * @var array
     */
    public array $searches = [];

    /**
     * An array of PopularCategoryModel instances representing popular categories.
     *
     * @var PopularCategoryModel[]
     */
    public array $categories = [];

    /**
     * An array of ProductModel instances representing popular products.
     *
     * @var ProductModel[]
     */
    public array $products = [];

    /**
     * Constructor for SearchRecommendationPopularModel.
     *
     * Processes raw data for categories and products:
     * - Converts each raw category array into a PopularCategoryModel instance.
     * - Converts each raw product array into a ProductModel instance.
     *
     * The raw popular search terms are assigned directly.
     *
     * @param array $searches    Raw array of popular search terms.
     * @param array $categories  Raw array of category data.
     * @param array $products    Raw array of product data.
     */
    public function __construct(array $searches, array $categories, array $products)
    {
        // Process raw category data into PopularCategoryModel instances.
        foreach ($categories as $key => $category) {
            $categories[$key] = new PopularCategoryModel(
                $category['id'],
                $category['name'],
                $category['image_url'],
                $category['url'],
                $category['custom'], // Adjusted order: if "segments" is required, ensure the correct value is passed.
                $category['position'],
                $category['created_at'],
                $category['updated_at']
            );
        }

        // Process raw product data into ProductModel instances.
        foreach ($products as $key => $product) {
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
                (array)$product['attributes'],
                (array)$product['variants'],
                (string)$product['brand']
            );
        }

        // Assign processed arrays to class properties.
        $this->searches = $searches;
        $this->categories = $categories;
        $this->products = $products;
    }
}

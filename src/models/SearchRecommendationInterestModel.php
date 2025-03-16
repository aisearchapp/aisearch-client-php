<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class SearchRecommendationInterestModel
 *
 * This model represents the interest-based recommendation data for a search.
 * It holds two arrays:
 * - 'clicks': an array of products that received clicks.
 * - 'products': an array of recommended product models.
 *
 * The constructor processes raw product data and converts each element into a
 * structured ProductModel instance.
 */
class SearchRecommendationInterestModel
{
    /**
     * An array of ProductModel instances representing clicked products.
     *
     * @var ProductModel[]
     */
    public array $clicks = [];

    /**
     * An array of ProductModel instances representing recommended products.
     *
     * @var ProductModel[]
     */
    public array $products = [];

    /**
     * Constructor for SearchRecommendationInterestModel.
     *
     * Processes raw arrays of product data from the API and converts them into
     * ProductModel instances. The raw data for both 'clicks' and 'products' is expected
     * to be an array of associative arrays.
     *
     * @param array $clicks    Raw data for clicked products.
     * @param array $products  Raw data for recommended products.
     */
    public function __construct(array $clicks, array $products)
    {
        // Process the raw data for clicked products.
        foreach ($clicks as $key => $click) {
            $clicks[$key] = new ProductModel(
                (int)$click['id'],
                (string)$click['name'],
                (array)$click['images'],
                (string)$click['url'],
                (int)$click['stock'],
                (bool)$click['is_new'],
                (float)$click['buying_price'],
                (float)$click['price'],
                (string)$click['currency_code'],
                (int)$click['category_id'],
                (int)$click['brand_id'],
                (string)$click['sku'],
                (string)$click['master_key'],
                (string)$click['barcode'],
                $click['custom'],
                (array)$click['attributes'],
                (array)$click['variants'],
                (string)$click['brand']
            );
        }

        // Process the raw data for recommended products.
        foreach ($products as $key => $product) {
            $products[$key] = new ProductModel(
                $product['id'],
                $product['name'],
                $product['images'],
                $product['url'],
                $product['stock'],
                $product['is_new'],
                $product['buying_price'],
                $product['price'],
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

        // Assign the processed arrays to the class properties.
        $this->clicks = $clicks;
        $this->products = $products;
    }
}

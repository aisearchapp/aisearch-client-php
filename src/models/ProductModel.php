<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class ProductModel
 *
 * This model represents a product entity within the Aisearch SDK.
 * It encapsulates various product details including its identifier, name, images, URLs,
 * stock information, pricing, currency, category and brand references, SKU, barcode,
 * custom data, attributes, available variants, and brand name.
 */
class ProductModel
{
    /**
     * The unique identifier of the product.
     *
     * @var int
     */
    public int $id;

    /**
     * The name of the product.
     *
     * @var string
     */
    public string $name;

    /**
     * An array of image URLs associated with the product.
     *
     * @var array
     */
    public array $images;

    /**
     * The URL to the product's detail page.
     *
     * @var string
     */
    public string $url;

    /**
     * The available stock count for the product.
     *
     * @var int
     */
    public int $stock;

    /**
     * Flag indicating whether the product is new.
     *
     * @var bool
     */
    public bool $is_new;

    /**
     * The base price of the product.
     *
     * @var float
     */
    public float $base_price;

    /**
     * The current price of the product.
     *
     * @var float
     */
    public float $price;

    /**
     * The currency code used for the product pricing (e.g., USD, EUR).
     *
     * @var string
     */
    public string $currency_code;

    /**
     * The identifier of the category to which the product belongs.
     *
     * @var int
     */
    public int $category_id;

    /**
     * The identifier of the brand to which the product belongs.
     *
     * @var int
     */
    public int $brand_id;

    /**
     * The Stock Keeping Unit (SKU) of the product.
     *
     * @var string
     */
    public string $sku;

    /**
     * A master key used for product grouping or identification.
     *
     * @var string
     */
    public string $master_key;

    /**
     * The barcode of the product.
     *
     * @var string
     */
    public string $barcode;

    /**
     * Custom data associated with the product.
     *
     * @var mixed
     */
    public $custom;

    /**
     * An array of product attributes.
     *
     * @var array
     */
    public array $attributes;

    /**
     * An array of product variant models.
     *
     * @var ProductVariantModel[]
     */
    public array $variants = [];

    /**
     * The name of the brand.
     *
     * @var string
     */
    public string $brand;

    /**
     * Constructor for the ProductModel.
     *
     * Initializes the product with all required details.
     *
     * @param int $id The unique identifier of the product.
     * @param string $name The name of the product.
     * @param array $images An array of image URLs for the product.
     * @param string $url The URL of the product's detail page.
     * @param int $stock The available stock count.
     * @param bool $is_new Indicates if the product is new.
     * @param float $base_price The base price of the product.
     * @param float $price The current price of the product.
     * @param string $currency_code The currency code (e.g., USD).
     * @param int $category_id The category identifier.
     * @param int $brand_id The brand identifier.
     * @param string $sku The product SKU.
     * @param string $master_key The master key for the product.
     * @param string $barcode The product barcode.
     * @param mixed $custom Custom data associated with the product.
     * @param array $attributes An array of product attributes.
     * @param ProductVariantModel[] $variants An array of product variant models.
     * @param string $brand The brand name.
     */
    public function __construct(
        int $id,
        string $name,
        array $images,
        string $url,
        int $stock,
        bool $is_new,
        float $base_price,
        float $price,
        string $currency_code,
        int $category_id,
        int $brand_id,
        string $sku,
        string $master_key,
        string $barcode,
        $custom,
        array $attributes,
        array $variants,
        string $brand
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->images = $images;
        $this->url = $url;
        $this->stock = $stock;
        $this->is_new = $is_new;
        $this->base_price = $base_price;
        $this->price = $price;
        $this->currency_code = $currency_code;
        $this->category_id = $category_id;
        $this->brand_id = $brand_id;
        $this->sku = $sku;
        $this->master_key = $master_key;
        $this->barcode = $barcode;
        $this->custom = $custom;
        $this->attributes = $attributes;
        $this->variants = $variants;
        $this->brand = $brand;
    }
}

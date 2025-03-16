<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class ProductVariantModel
 *
 * This model represents a variant of a product.
 * A product variant can differ in attributes such as price, stock, SKU, and other custom data.
 */
class ProductVariantModel
{
    /**
     * The name of the variant.
     *
     * @var string
     */
    public string $name;

    /**
     * The available stock for this variant.
     *
     * @var int
     */
    public int $stock;

    /**
     * The base price of the variant.
     *
     * @var float
     */
    public float $base_price;

    /**
     * The current price of the variant.
     *
     * @var float
     */
    public float $price;

    /**
     * The Stock Keeping Unit (SKU) of the variant.
     *
     * @var string
     */
    public string $sku;

    /**
     * A master key used for grouping or uniquely identifying the variant.
     *
     * @var string
     */
    public string $master_key;

    /**
     * Custom data associated with the variant.
     *
     * @var mixed
     */
    public $custom;

    /**
     * An array of attributes specific to this variant.
     *
     * @var array
     */
    public array $attributes;

    /**
     * Constructor for the ProductVariantModel.
     *
     * Initializes the product variant with the provided values.
     *
     * @param string $name The name of the variant.
     * @param int $stock The available stock for the variant.
     * @param float $base_price The base price of the variant.
     * @param float $price The current price of the variant.
     * @param string $sku The SKU for the variant.
     * @param string $master_key The master key for the variant.
     * @param mixed $custom Custom data associated with the variant.
     * @param array $attributes An array of attributes for the variant.
     */
    public function __construct(
        string $name,
        int $stock,
        float $base_price,
        float $price,
        string $sku,
        string $master_key,
               $custom,
        array $attributes
    ) {
        $this->name = $name;
        $this->stock = $stock;
        $this->base_price = $base_price;
        $this->price = $price;
        $this->sku = $sku;
        $this->master_key = $master_key;
        $this->custom = $custom;
        $this->attributes = $attributes;
    }
}

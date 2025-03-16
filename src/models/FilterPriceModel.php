<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class FilterPriceModel
 *
 * This model represents a price range filter.
 * It encapsulates a minimum and maximum price, which can be used to filter results based on a price range.
 */
class FilterPriceModel
{
    /**
     * The minimum price value.
     *
     * @var float
     */
    public float $min;

    /**
     * The maximum price value.
     *
     * @var float
     */
    public float $max;

    /**
     * Constructor for the FilterPriceModel.
     *
     * Initializes the price range with the provided minimum and maximum values.
     *
     * @param float $min The minimum price.
     * @param float $max The maximum price.
     */
    public function __construct(float $min, float $max)
    {
        $this->min = $min;
        $this->max = $max;
    }
}

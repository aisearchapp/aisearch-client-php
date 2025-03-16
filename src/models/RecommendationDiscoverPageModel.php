<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class RecommendationDiscoverPageModel
 *
 * This model represents pagination details for discovery-based recommendations.
 * It contains information about the pagination limit, the total count of items,
 * whether there is a next page available, and a cursor (or identifier) for the next page.
 */
class RecommendationDiscoverPageModel
{
    /**
     * The maximum number of items per page.
     *
     * @var int
     */
    public int $limit;

    /**
     * The total number of items in the current page.
     *
     * @var int
     */
    public int $count;

    /**
     * Flag indicating if there is a next page of results.
     *
     * @var bool
     */
    public bool $has_next;

    /**
     * The cursor or identifier used to fetch the next page.
     *
     * @var string
     */
    public string $after;

    /**
     * Constructor for the RecommendationDiscoverPageModel.
     *
     * Initializes the pagination model with the provided values.
     *
     * @param int $limit    The maximum number of items per page.
     * @param int $count    The total number of items in the current page.
     * @param bool $has_next Whether there is a next page available.
     * @param string $after The cursor or identifier for the next page.
     */
    public function __construct(int $limit, int $count, bool $has_next, string $after)
    {
        $this->limit = $limit;
        $this->count = $count;
        $this->has_next = $has_next;
        $this->after = $after;
    }
}

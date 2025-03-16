<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class PageModel
 *
 * This model represents pagination information for API responses.
 * It includes the total count of items and the next page number.
 */
class PageModel
{
    /**
     * The total number of items available.
     *
     * @var int
     */
    public int $count;

    /**
     * The page number of the next set of results.
     *
     * @var int
     */
    public int $next;

    /**
     * Constructor for the PageModel.
     *
     * Initializes the pagination model with the given count and next page values.
     *
     * @param int $count The total number of items available.
     * @param int $next  The page number for the next set of results.
     */
    public function __construct(int $count, int $next)
    {
        $this->count = $count;
        $this->next = $next;
    }
}

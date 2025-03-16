<?php
declare(strict_types=1);

namespace AisearchClient\controllers;

use AisearchClient\Controller;
use AisearchClient\controllers\Search\QueryAction;
use AisearchClient\controllers\Search\RecommendationAction;

/**
 * Class SearchController
 *
 * This controller manages search-related operations for the Aisearch SDK.
 * It provides factory methods to create actions for search queries and recommendations.
 */
class SearchController extends Controller
{
    /**
     * Creates and returns a QueryAction instance.
     *
     * QueryAction is responsible for handling search query requests.
     * It takes the current controller instance as a dependency to inherit common properties.
     *
     * @return QueryAction
     */
    public function query(): QueryAction
    {
        return new QueryAction($this);
    }

    /**
     * Creates and returns a RecommendationAction instance.
     *
     * RecommendationAction handles the retrieval of search recommendations.
     * It also receives the current controller instance to utilize shared configuration.
     *
     * @return RecommendationAction
     */
    public function recommendation(): RecommendationAction
    {
        return new RecommendationAction($this);
    }
}

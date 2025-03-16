<?php
declare(strict_types=1);

namespace AisearchClient\controllers;

use AisearchClient\Controller;
use AisearchClient\controllers\Recommendation\CarouselAction;
use AisearchClient\controllers\Recommendation\DiscoverAction;

/**
 * Class RecommendationController
 *
 * This controller handles recommendation-related actions in the Aisearch SDK.
 * It provides factory methods to create specific recommendation actions.
 */
class RecommendationController extends Controller
{
    /**
     * Creates and returns a new CarouselAction instance.
     *
     * CarouselAction is used to retrieve carousel-style recommendations.
     *
     * @return CarouselAction
     */
    public function carousel(): CarouselAction
    {
        return new CarouselAction($this);
    }

    /**
     * Creates and returns a new DiscoverAction instance.
     *
     * DiscoverAction is used to retrieve discovery-based recommendations.
     *
     * @return DiscoverAction
     */
    public function discover(): DiscoverAction
    {
        return new DiscoverAction($this);
    }
}

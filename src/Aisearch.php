<?php
declare(strict_types=1);

namespace AisearchClient\src;

// Import controller classes that manage specific API functionalities.
use AisearchClient\controllers\RecommendationController;
use AisearchClient\controllers\SearchController;
use AisearchClient\controllers\SearchRecentQueryController;
use AisearchClient\controllers\SettingsController;

/**
 * Class Aisearch
 *
 * Main SDK client class for interacting with the Aisearch API.
 * Provides factory methods to access different API controllers.
 */
class Aisearch
{
    /**
     * Base API URL.
     */
    const API = 'https://api.aisearch.app';

    /**
     * API version used in requests.
     */
    const API_VERSION = 'v1';

    /**
     * The site identifier used for API calls.
     *
     * @var int
     */
    private int $site_id;

    /**
     * The client token used for authenticating API requests.
     *
     * @var string
     */
    private string $client_token;

    /**
     * Aisearch constructor.
     *
     * @param int $site_id The site ID for the API.
     * @param string $client_token The client token for authentication.
     */
    public function __construct(int $site_id, string $client_token)
    {
        $this->site_id = $site_id;
        $this->client_token = $client_token;
    }

    /**
     * Creates and returns a new SearchController instance.
     *
     * @return SearchController Controller to handle search requests.
     */
    public function search(): SearchController
    {
        $controller = new SearchController();
        // Pass the site ID and client token to the controller.
        $controller->site_id = $this->site_id;
        $controller->client_token = $this->client_token;
        return $controller;
    }

    /**
     * Creates and returns a new SettingsController instance.
     *
     * @return SettingsController Controller to manage settings.
     */
    public function settings(): SettingsController
    {
        $controller = new SettingsController();
        // Pass the site ID and client token to the controller.
        $controller->site_id = $this->site_id;
        $controller->client_token = $this->client_token;
        return $controller;
    }

    /**
     * Creates and returns a new SearchRecentQueryController instance.
     *
     * @return SearchRecentQueryController Controller for recent search queries.
     */
    public function searchRecentQuery(): SearchRecentQueryController
    {
        $controller = new SearchRecentQueryController();
        // Pass the site ID and client token to the controller.
        $controller->site_id = $this->site_id;
        $controller->client_token = $this->client_token;
        return $controller;
    }

    /**
     * Creates and returns a new RecommendationController instance.
     *
     * @return RecommendationController Controller to manage recommendations.
     */
    public function recommendation(): RecommendationController
    {
        $controller = new RecommendationController();
        // Pass the site ID and client token to the controller.
        $controller->site_id = $this->site_id;
        $controller->client_token = $this->client_token;
        return $controller;
    }
}

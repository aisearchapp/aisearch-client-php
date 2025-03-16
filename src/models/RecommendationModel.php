<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class RecommendationModel
 *
 * This model encapsulates recommendation data that is used in the Aisearch SDK.
 * It includes two main parts:
 *  - A "relating" section, which contains attributes and page redirects processed into structured models.
 *  - An "autocomplete" section that holds autocomplete suggestion data.
 */
class RecommendationModel
{
    /**
     * A model containing related recommendation data, such as attributes and page redirects.
     *
     * @var RecommendationRelatingModel
     */
    public RecommendationRelatingModel $relating;

    /**
     * An array of autocomplete suggestions.
     *
     * @var array
     */
    public array $autocomplete;

    /**
     * Constructor for RecommendationModel.
     *
     * This constructor processes raw recommendation data provided as an array for the "relating" part.
     * It converts:
     * - Each raw attribute in the 'attributes' key into a RecommendationRelatingAttributeParentModel instance.
     * - Each raw page redirect in the 'pageRedirects' key into a RecommendationRelatingPageRedirectModel instance.
     *
     * After processing, it initializes the $relating property as a new RecommendationRelatingModel instance
     * using the processed attributes and page redirects. The autocomplete data is assigned directly.
     *
     * @param array $relating       Raw relating data with keys 'attributes' and 'pageRedirects'.
     * @param array $autocomplete   Raw autocomplete suggestion data.
     */
    public function __construct(array $relating, array $autocomplete)
    {
        // Process each raw attribute into a structured model instance.
        foreach ($relating['attributes'] as $key => $attribute) {
            $relating['attributes'][$key] = new RecommendationRelatingAttributeParentModel(
                $attribute['id'],
                $attribute['name'],
                $attribute['filter_label'],
                $attribute['filter_type'],
                (bool)$attribute['show_in_full_search'],
                (bool)$attribute['show_in_recommendation'],
                $attribute['recommendation_title'],
                (bool)$attribute['is_option'],
                $attribute['children']
            );
        }

        // Process each raw page redirect into a structured model instance.
        foreach ($relating['pageRedirects'] as $key => $pageRedirect) {
            $relating['pageRedirects'][$key] = new RecommendationRelatingPageRedirectModel(
                $pageRedirect['id'],
                $pageRedirect['name'],
                $pageRedirect['url'],
                $pageRedirect['auto_redirect'],
                $pageRedirect['position'],
                $pageRedirect['type'],
                $pageRedirect['detail'],
                $pageRedirect['created_at'],
                $pageRedirect['updated_at']
            );
        }

        // Initialize the "relating" property using the processed attributes and page redirects.
        $this->relating = new RecommendationRelatingModel(
            $relating['attributes'],
            $relating['pageRedirects']
        );

        // Assign autocomplete data directly.
        $this->autocomplete = $autocomplete;
    }
}

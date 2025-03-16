<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class RecommendationRelatingModel
 *
 * This model encapsulates recommendation relating data.
 * It contains:
 * - An array of parent attribute models (RecommendationRelatingAttributeParentModel)
 *   representing the attributes used for recommendations.
 * - An array of page redirect models (RecommendationRelatingPageRedirectModel)
 *   that define redirection behavior for recommendation pages.
 */
class RecommendationRelatingModel
{
    /**
     * An array of recommendation-related parent attribute models.
     *
     * @var RecommendationRelatingAttributeParentModel[]
     */
    public array $attributes;

    /**
     * An array of page redirect models for recommendations.
     *
     * @var RecommendationRelatingPageRedirectModel[]
     */
    public array $page_redirects;

    /**
     * Constructor for RecommendationRelatingModel.
     *
     * Initializes the model with the given arrays of parent attribute models and page redirect models.
     *
     * @param RecommendationRelatingAttributeParentModel[] $attributes    Array of parent attribute models.
     * @param RecommendationRelatingPageRedirectModel[]      $page_redirects Array of page redirect models.
     */
    public function __construct(array $attributes, array $page_redirects)
    {
        $this->attributes = $attributes;
        $this->page_redirects = $page_redirects;
    }
}

<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class SearchRecommendationModel
 *
 * This model encapsulates recommendation data generated from a search query.
 * It contains various pieces of information including:
 * - An array of parent attribute models used for recommendations.
 * - An array of child attribute models.
 * - An interest model containing clicked products and additional recommended products.
 * - A popular model containing popular search data.
 * - A CTA (Call-To-Action) model for recommendation messages.
 * - An array of recent search queries.
 *
 * The constructor processes raw data for attribute parents and attributes, converting them
 * into their respective model instances.
 */
class SearchRecommendationModel
{
    /**
     * An array of AttributeParentModel instances representing the parent attributes for recommendations.
     *
     * @var AttributeParentModel[]
     */
    public array $attribute_parents;

    /**
     * An array of AttributeChildModel instances representing the child attributes for recommendations.
     *
     * @var AttributeChildModel[]
     */
    public array $attributes;

    /**
     * A SearchRecommendationInterestModel instance that holds interest-based recommendation data.
     *
     * @var SearchRecommendationInterestModel
     */
    public SearchRecommendationInterestModel $interests;

    /**
     * A SearchRecommendationPopularModel instance that holds popular search data.
     *
     * @var SearchRecommendationPopularModel
     */
    public SearchRecommendationPopularModel $popular;

    /**
     * A SearchRecommendationCtaModel instance that holds Call-To-Action (CTA) data for recommendations.
     *
     * @var SearchRecommendationCtaModel
     */
    public SearchRecommendationCtaModel $cta;

    /**
     * An array of recent search queries.
     *
     * @var array
     */
    public array $recent;

    /**
     * Constructor for SearchRecommendationModel.
     *
     * Processes raw data for attribute parents and attributes, converting each element
     * into their respective model instances. It also assigns the provided interest,
     * popular, CTA, and recent search data to the corresponding properties.
     *
     * @param array $attribute_parents Raw array of parent attribute data.
     *                                 Each element should be an associative array with keys like 'id', 'group_id', etc.
     * @param array $attributes        Raw array of child attribute data.
     *                                 Each element should be an associative array with keys like 'id', 'parent_id', etc.
     * @param SearchRecommendationInterestModel $interests An instance of SearchRecommendationInterestModel.
     * @param SearchRecommendationPopularModel $popular      An instance of SearchRecommendationPopularModel.
     * @param SearchRecommendationCtaModel $cta              An instance of SearchRecommendationCtaModel.
     * @param array $recent            Array of recent search queries.
     */
    public function __construct(
        array $attribute_parents,
        array $attributes,
        SearchRecommendationInterestModel $interests,
        SearchRecommendationPopularModel $popular,
        SearchRecommendationCtaModel $cta,
        array $recent
    ) {
        // Process each raw parent attribute data into an AttributeParentModel instance.
        foreach ($attribute_parents as $key => $attribute_parent) {
            $attribute_parents[$key] = new AttributeParentModel(
                (int)$attribute_parent['id'],
                (int)$attribute_parent['group_id'],
                (int)$attribute_parent['position'],
                (string)$attribute_parent['name'],
                (string)$attribute_parent['regular_name'],
                (string)$attribute_parent['filter_label'],
                (string)$attribute_parent['filter_type'],
                (string)$attribute_parent['remote_key'],
                (bool)$attribute_parent['show_in_full_search'],
                (bool)$attribute_parent['show_in_recommendation'],
                (string)$attribute_parent['recommendation_title'],
                (bool)$attribute_parent['is_option'],
                (string)$attribute_parent['created_at'],
                (string)$attribute_parent['updated_at']
            );
        }

        // Process each raw child attribute data into an AttributeChildModel instance.
        foreach ($attributes as $key => $attribute) {
            $attributes[$key] = new AttributeChildModel(
                (int)$attribute['id'],
                (int)$attribute['parent_id'],
                (int)$attribute['group_id'],
                (int)$attribute['position'],
                (string)$attribute['name'],
                (string)$attribute['regular_name'],
                (string)$attribute['filter_label'],
                (string)$attribute['color_code'],
                (string)$attribute['remote_key'],
                (string)$attribute['created_at'],
                (string)$attribute['updated_at']
            );
        }

        // Assign processed data to class properties.
        $this->attribute_parents = $attribute_parents;
        $this->attributes = $attributes;
        $this->interests = $interests;
        $this->popular = $popular;
        $this->cta = $cta;
        $this->recent = $recent;
    }
}

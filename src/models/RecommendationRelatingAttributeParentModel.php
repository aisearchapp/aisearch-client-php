<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class RecommendationRelatingAttributeParentModel
 *
 * This model represents a parent attribute in the context of recommendations.
 * It holds basic information such as ID, name, filtering details, and flags indicating
 * where the attribute should be displayed. Additionally, it processes and stores an array
 * of child attributes as instances of RecommendationRelatingAttributeChildModel.
 */
class RecommendationRelatingAttributeParentModel
{
    /**
     * The unique identifier of the parent attribute.
     *
     * @var int
     */
    public int $id;

    /**
     * The name of the parent attribute.
     *
     * @var string
     */
    public string $name;

    /**
     * The label used for filtering the attribute.
     *
     * @var string
     */
    public string $filter_label;

    /**
     * The type of filter applied to the attribute.
     *
     * @var string
     */
    public string $filter_type;

    /**
     * Flag indicating whether this attribute should be displayed in full search.
     *
     * @var bool
     */
    public bool $show_in_full_search;

    /**
     * Flag indicating whether this attribute should be shown in recommendations.
     *
     * @var bool
     */
    public bool $show_in_recommendation;

    /**
     * The title used for recommendations related to this attribute.
     *
     * @var string
     */
    public string $recommendation_title;

    /**
     * Flag indicating if this attribute is an option type.
     *
     * @var bool
     */
    public bool $is_option;

    /**
     * An array of child attribute models associated with this parent attribute.
     *
     * @var RecommendationRelatingAttributeChildModel[]
     */
    public array $children = [];

    /**
     * Constructor for RecommendationRelatingAttributeParentModel.
     *
     * Processes raw child attribute data and converts each element into a
     * RecommendationRelatingAttributeChildModel instance.
     *
     * @param int    $id                    The unique identifier of the parent attribute.
     * @param string $name                  The name of the parent attribute.
     * @param string $filter_label          The label used for filtering.
     * @param string $filter_type           The type of filter applied.
     * @param bool   $show_in_full_search   Whether the attribute is displayed in full search.
     * @param bool   $show_in_recommendation Whether the attribute is displayed in recommendations.
     * @param string $recommendation_title  The title used in recommendation displays.
     * @param bool   $is_option             Whether the attribute is considered an option.
     * @param array  $children              Raw array of child attributes data.
     */
    public function __construct(
        int $id,
        string $name,
        string $filter_label,
        string $filter_type,
        bool $show_in_full_search,
        bool $show_in_recommendation,
        string $recommendation_title,
        bool $is_option,
        array $children
    ) {
        // Process each raw child attribute data into a structured model instance.
        foreach ($children as $key => $child) {
            $children[$key] = new RecommendationRelatingAttributeChildModel(
                $child['id'],
                $child['parent_id'],
                $child['group_id'],
                $child['position'],
                $child['name'],
                $child['filter_label'],
                $child['color_code']
            );
        }
        // Assign values to the parent attribute properties.
        $this->id = $id;
        $this->name = $name;
        $this->filter_label = $filter_label;
        $this->filter_type = $filter_type;
        $this->show_in_full_search = $show_in_full_search;
        $this->show_in_recommendation = $show_in_recommendation;
        $this->recommendation_title = $recommendation_title;
        $this->is_option = $is_option;
        $this->children = $children;
    }
}

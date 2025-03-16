<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class FilterAttributeParentModel
 *
 * This model represents a parent attribute used for filtering.
 * It encapsulates key details of the parent attribute and includes an array
 * of its child attributes as instances of FilterAttributeChildModel.
 */
class FilterAttributeParentModel
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
     * The label used for filtering purposes.
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
     * Flag indicating whether this attribute should be shown in full search.
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
     * Flag indicating whether this attribute is considered an option.
     *
     * @var bool
     */
    public bool $is_option;

    /**
     * An array of child attribute models associated with this parent attribute.
     *
     * @var FilterAttributeChildModel[]
     */
    public array $children = [];

    /**
     * Constructor for FilterAttributeParentModel.
     *
     * Initializes the parent attribute with the provided values and converts the raw children data
     * into an array of FilterAttributeChildModel instances.
     *
     * @param int    $id                    Unique identifier for the parent attribute.
     * @param string $name                  Name of the attribute.
     * @param string $filter_label          Filter label used for display purposes.
     * @param string $filter_type           Type of filter applied.
     * @param bool   $show_in_full_search   Indicates if the attribute is visible in full search.
     * @param bool   $show_in_recommendation Indicates if the attribute is visible in recommendations.
     * @param string $recommendation_title  Title to be used in recommendation displays.
     * @param bool   $is_option             Indicates if the attribute is an option.
     * @param array  $children              An array of associative arrays representing child attributes.
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
        // Convert each raw child attribute into a FilterAttributeChildModel instance.
        foreach ($children as $key => $child) {
            $children[$key] = new FilterAttributeChildModel(
                $child['id'],
                $child['parent_id'],
                $child['group_id'],
                $child['position'],
                $child['name'],
                $child['filter_label'],
                $child['color_code'],
                (int)$child['count']
            );
        }
        // Initialize parent attribute properties.
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

<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class AttributeParentModel
 *
 * This model represents a parent attribute entity.
 * It encapsulates various details about a parent attribute, including its grouping,
 * display order, names, filter settings, remote key for external references, and visibility flags.
 * Additionally, it holds metadata such as creation and update timestamps.
 */
class AttributeParentModel
{
    /**
     * The unique identifier of the parent attribute.
     *
     * @var int
     */
    public int $id;

    /**
     * The identifier of the group this attribute belongs to.
     *
     * @var int
     */
    public int $group_id;

    /**
     * The display order position of the attribute.
     *
     * @var int
     */
    public int $position;

    /**
     * The name of the attribute.
     *
     * @var string
     */
    public string $name;

    /**
     * The normalized or regular name of the attribute.
     *
     * @var string
     */
    public string $regular_name;

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
     * The remote key used for external identification.
     *
     * @var string
     */
    public string $remote_key;

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
     * The title used in recommendations for this attribute.
     *
     * @var string
     */
    public string $recommendation_title;

    /**
     * Flag indicating whether this attribute is an option type.
     *
     * @var bool
     */
    public bool $is_option;

    /**
     * The timestamp when the attribute was created.
     *
     * @var string
     */
    public string $created_at;

    /**
     * The timestamp when the attribute was last updated.
     *
     * @var string
     */
    public string $updated_at;

    /**
     * Constructor for the AttributeParentModel.
     *
     * Initializes the parent attribute with the provided values.
     *
     * @param int $id The unique identifier of the parent attribute.
     * @param int $group_id The identifier of the group the attribute belongs to.
     * @param int $position The display order of the attribute.
     * @param string $name The name of the attribute.
     * @param string $regular_name The normalized name of the attribute.
     * @param string $filter_label The label used for filtering.
     * @param string $filter_type The type of filter applied.
     * @param string $remote_key The remote key for external reference.
     * @param bool $show_in_full_search Whether to display the attribute in full search.
     * @param bool $show_in_recommendation Whether to display the attribute in recommendations.
     * @param string $recommendation_title The title for recommendations.
     * @param bool $is_option Indicates if the attribute is an option.
     * @param string $created_at The creation timestamp.
     * @param string $updated_at The last update timestamp.
     */
    public function __construct(
        int $id,
        int $group_id,
        int $position,
        string $name,
        string $regular_name,
        string $filter_label,
        string $filter_type,
        string $remote_key,
        bool $show_in_full_search,
        bool $show_in_recommendation,
        string $recommendation_title,
        bool $is_option,
        string $created_at,
        string $updated_at
    ) {
        $this->id = $id;
        $this->group_id = $group_id;
        $this->position = $position;
        $this->name = $name;
        $this->regular_name = $regular_name;
        $this->filter_label = $filter_label;
        $this->filter_type = $filter_type;
        $this->remote_key = $remote_key;
        $this->show_in_full_search = $show_in_full_search;
        $this->show_in_recommendation = $show_in_recommendation;
        $this->recommendation_title = $recommendation_title;
        $this->is_option = $is_option;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}

<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class RecommendationRelatingAttributeChildModel
 *
 * This model represents a child attribute in the context of recommendation-related data.
 * It stores essential details such as the unique identifier, its parent association,
 * grouping, display position, name, filter label, and associated color code.
 */
class RecommendationRelatingAttributeChildModel
{
    /**
     * The unique identifier of the child attribute.
     *
     * @var int
     */
    public int $id;

    /**
     * The identifier of the parent attribute to which this child belongs.
     *
     * @var int
     */
    public int $parent_id;

    /**
     * The identifier of the group this child attribute is associated with.
     *
     * @var int
     */
    public int $group_id;

    /**
     * The display order position of this child attribute.
     *
     * @var int
     */
    public int $position;

    /**
     * The name of the child attribute.
     *
     * @var string
     */
    public string $name;

    /**
     * The filter label used for displaying the child attribute in filter contexts.
     *
     * @var string
     */
    public string $filter_label;

    /**
     * The color code associated with this child attribute, used for UI display.
     *
     * @var string
     */
    public string $color_code;

    /**
     * Constructor for RecommendationRelatingAttributeChildModel.
     *
     * Initializes the child attribute with the provided values.
     *
     * @param int    $id            The unique identifier of the child attribute.
     * @param int    $parent_id     The identifier of the parent attribute.
     * @param int    $group_id      The group identifier for the attribute.
     * @param int    $position      The display order position.
     * @param string $name          The name of the attribute.
     * @param string $filter_label  The label used for filtering.
     * @param string $color_code    The color code associated with the attribute.
     */
    public function __construct(
        int $id,
        int $parent_id,
        int $group_id,
        int $position,
        string $name,
        string $filter_label,
        string $color_code
    ) {
        $this->id = $id;
        $this->parent_id = $parent_id;
        $this->group_id = $group_id;
        $this->position = $position;
        $this->name = $name;
        $this->filter_label = $filter_label;
        $this->color_code = $color_code;
    }
}

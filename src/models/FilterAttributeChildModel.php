<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class FilterAttributeChildModel
 *
 * This model represents a child attribute used for filtering purposes.
 * It includes details such as the attribute's identifier, its parent relationship,
 * grouping information, display position, naming details, label for filtering,
 * associated color code, and the count of items associated with this attribute.
 */
class FilterAttributeChildModel
{
    /**
     * The unique identifier of the filter attribute child.
     *
     * @var int
     */
    public int $id;

    /**
     * The identifier of the parent attribute.
     *
     * @var int
     */
    public int $parent_id;

    /**
     * The identifier of the group to which this attribute belongs.
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
     * The label used for filtering purposes.
     *
     * @var string
     */
    public string $filter_label;

    /**
     * The color code associated with the attribute, often used for UI display.
     *
     * @var string
     */
    public string $color_code;

    /**
     * The count of items associated with this attribute.
     *
     * @var int
     */
    public int $count;

    /**
     * Constructor for the FilterAttributeChildModel.
     *
     * Initializes the filter attribute child with the provided values.
     *
     * @param int    $id            The unique identifier of the attribute.
     * @param int    $parent_id     The identifier of the parent attribute.
     * @param int    $group_id      The group identifier to which this attribute belongs.
     * @param int    $position      The display order position of the attribute.
     * @param string $name          The name of the attribute.
     * @param string $filter_label  The label used for filtering.
     * @param string $color_code    The color code for UI display.
     * @param int    $count         The count of items associated with this attribute.
     */
    public function __construct(
        int $id,
        int $parent_id,
        int $group_id,
        int $position,
        string $name,
        string $filter_label,
        string $color_code,
        int $count
    ) {
        $this->id = $id;
        $this->parent_id = $parent_id;
        $this->group_id = $group_id;
        $this->position = $position;
        $this->name = $name;
        $this->filter_label = $filter_label;
        $this->color_code = $color_code;
        $this->count = $count;
    }
}

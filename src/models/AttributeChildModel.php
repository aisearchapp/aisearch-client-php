<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class AttributeChildModel
 *
 * This model represents a child attribute entity.
 * It stores information about an attribute including its unique ID, its parent relation,
 * grouping details, display position, naming details, filter label, color information,
 * remote key, and timestamps for creation and last update.
 */
class AttributeChildModel
{
    /**
     * The unique identifier of the attribute.
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
     * The color code associated with the attribute.
     *
     * @var string
     */
    public string $color_code;

    /**
     * The remote key used for external identification of the attribute.
     *
     * @var string
     */
    public string $remote_key;

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
     * Constructor for the AttributeChildModel.
     *
     * Initializes the attribute properties with the provided values.
     *
     * @param int $id The unique identifier of the attribute.
     * @param int $parent_id The identifier of the parent attribute.
     * @param int $group_id The identifier of the group the attribute belongs to.
     * @param int $position The display order of the attribute.
     * @param string $name The name of the attribute.
     * @param string $regular_name The normalized name of the attribute.
     * @param string $filter_label The filter label used for the attribute.
     * @param string $color_code The color code associated with the attribute.
     * @param string $remote_key The remote key for external reference.
     * @param string $created_at The creation timestamp of the attribute.
     * @param string $updated_at The last update timestamp of the attribute.
     */
    public function __construct(
        int $id,
        int $parent_id,
        int $group_id,
        int $position,
        string $name,
        string $regular_name,
        string $filter_label,
        string $color_code,
        string $remote_key,
        string $created_at,
        string $updated_at
    ) {
        $this->id = $id;
        $this->parent_id = $parent_id;
        $this->group_id = $group_id;
        $this->position = $position;
        $this->name = $name;
        $this->regular_name = $regular_name;
        $this->filter_label = $filter_label;
        $this->color_code = $color_code;
        $this->remote_key = $remote_key;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}

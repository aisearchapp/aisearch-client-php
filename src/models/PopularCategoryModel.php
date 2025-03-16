<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class PopularCategoryModel
 *
 * This model represents a popular category entity used within the Aisearch platform.
 * It stores details such as the category ID, name, image URL, associated URL, filtering segments,
 * custom settings, display position, and timestamps for creation and updates.
 */
class PopularCategoryModel
{
    /**
     * The unique identifier for the popular category.
     *
     * @var int
     */
    public int $id;

    /**
     * The name of the popular category.
     *
     * @var string
     */
    public string $name;

    /**
     * The URL of the image representing the category.
     *
     * @var string
     */
    public string $image_url;

    /**
     * The URL linking to the category's page.
     *
     * @var string
     */
    public string $url;


    /**
     * Custom settings or additional information associated with the category.
     *
     * @var string
     */
    public string $custom;

    /**
     * The display order position of the category.
     *
     * @var int
     */
    public int $position;

    /**
     * The timestamp when the category was created.
     *
     * @var string
     */
    public string $created_at;

    /**
     * The timestamp when the category was last updated.
     *
     * @var string
     */
    public string $updated_at;

    /**
     * Constructor for the PopularCategoryModel.
     *
     * Initializes the popular category model with the provided values.
     *
     * @param int    $id          The unique identifier for the category.
     * @param string $name        The name of the category.
     * @param string $image_url   The URL of the category image.
     * @param string $url         The link to the category page.
     * @param string $custom      Custom settings or additional information.
     * @param int    $position    The display order position of the category.
     * @param string $created_at  The creation timestamp.
     * @param string $updated_at  The last update timestamp.
     */
    public function __construct(
        int $id,
        string $name,
        string $image_url,
        string $url,
        string $custom,
        int $position,
        string $created_at,
        string $updated_at
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->image_url = $image_url;
        $this->url = $url;
        $this->custom = $custom;
        $this->position = $position;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}

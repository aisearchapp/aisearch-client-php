<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class RecommendationRelatingPageRedirectModel
 *
 * This model represents a page redirect configuration for recommendation pages.
 * It holds details such as the redirect's unique ID, name, target URL, whether it should
 * automatically redirect, its display position, type, additional details, and timestamps for creation and updates.
 */
class RecommendationRelatingPageRedirectModel
{
    /**
     * The unique identifier for the page redirect.
     *
     * @var int
     */
    public int $id;

    /**
     * The name of the page redirect.
     *
     * @var string
     */
    public string $name;

    /**
     * The URL to which the redirect points.
     *
     * @var string
     */
    public string $url;

    /**
     * Flag indicating whether the redirect should occur automatically.
     *
     * @var bool
     */
    public bool $auto_redirect;

    /**
     * The display order position of the redirect.
     *
     * @var int
     */
    public int $position;

    /**
     * The type of the redirect.
     *
     * @var string
     */
    public string $type;

    /**
     * Additional details for the redirect.
     *
     * @var mixed
     */
    public $detail;

    /**
     * The timestamp when the redirect was created.
     *
     * @var string
     */
    public string $created_at;

    /**
     * The timestamp when the redirect was last updated.
     *
     * @var string
     */
    public string $updated_at;

    /**
     * Constructor for RecommendationRelatingPageRedirectModel.
     *
     * Initializes the page redirect model with the provided values.
     *
     * @param int    $id            The unique identifier for the redirect.
     * @param string $name          The name of the redirect.
     * @param string $url           The target URL for the redirect.
     * @param bool   $auto_redirect Whether the redirect should happen automatically.
     * @param int    $position      The display order position of the redirect.
     * @param string $type          The type of the redirect.
     * @param mixed  $detail        Additional details for the redirect.
     * @param string $created_at    The creation timestamp.
     * @param string $updated_at    The last update timestamp.
     */
    public function __construct(
        int $id,
        string $name,
        string $url,
        bool $auto_redirect,
        int $position,
        string $type,
        $detail,
        string $created_at,
        string $updated_at
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->url = $url;
        $this->auto_redirect = $auto_redirect;
        $this->position = $position;
        $this->type = $type;
        $this->detail = $detail;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}

<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class SearchRecommendationCtaTypingModel
 *
 * This model represents a single Call-to-Action (CTA) typing element used in recommendations.
 * It stores an identifier and a message that can be used to display a CTA in the recommendation interface.
 */
class SearchRecommendationCtaTypingModel
{
    /**
     * The unique identifier for the CTA typing element.
     *
     * @var int
     */
    public int $id;

    /**
     * The message content of the CTA typing element.
     *
     * @var string
     */
    public string $message;

    /**
     * Constructor for SearchRecommendationCtaTypingModel.
     *
     * Initializes the CTA typing model with the provided ID and message.
     *
     * @param int $id The unique identifier for the CTA typing element.
     * @param string $message The message content for the CTA typing element.
     */
    public function __construct(int $id, string $message)
    {
        $this->id = $id;
        $this->message = $message;
    }
}

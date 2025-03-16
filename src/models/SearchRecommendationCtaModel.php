<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class SearchRecommendationCtaModel
 *
 * This model encapsulates Call-to-Action (CTA) data for recommendations.
 * It processes an array of raw CTA typing data into an array of
 * SearchRecommendationCtaTypingModel instances.
 */
class SearchRecommendationCtaModel
{
    /**
     * An array of CTA typing models.
     *
     * @var SearchRecommendationCtaTypingModel[]
     */
    public array $typing = [];

    /**
     * Constructor for SearchRecommendationCtaModel.
     *
     * Processes the provided raw CTA typing data by converting each
     * element into a SearchRecommendationCtaTypingModel instance.
     *
     * @param array $typing Raw array of CTA typing data.
     *                      Each element should contain 'id' and 'message' keys.
     */
    public function __construct(array $typing)
    {
        // Convert each raw item into a SearchRecommendationCtaTypingModel instance.
        foreach ($typing as $key => $item) {
            $typing[$key] = new SearchRecommendationCtaTypingModel(
                $item['id'],
                $item['message']
            );
        }
        // Assign the processed array to the typing property.
        $this->typing = $typing;
    }
}

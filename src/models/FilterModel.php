<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class FilterModel
 *
 * This model encapsulates filtering information used within the application.
 * It contains:
 * - An array of parent attribute models representing the available filter options.
 * - An associative array of selected child attribute IDs keyed by parent attribute ID.
 * - A price range filter encapsulated in a FilterPriceModel.
 */
class FilterModel
{
    /**
     * An array of FilterAttributeParentModel instances representing the available filter attributes.
     *
     * @var FilterAttributeParentModel[]
     */
    public array $attributes;

    /**
     * An associative array of selected attribute IDs.
     *
     * Structure example: [parent_id => [child_id1, child_id2, ...]]
     *
     * @var array<int, array>
     */
    private array $selected;

    /**
     * The price range filter represented by a FilterPriceModel.
     *
     * @var FilterPriceModel
     */
    public FilterPriceModel $price;

    /**
     * FilterModel constructor.
     *
     * Initializes the filter model with selected attributes, available attributes, and a price range.
     *
     * @param array $selected   An associative array of selected attribute IDs (e.g., [parent_id => [child_id1, child_id2]]).
     * @param array $attributes An array of associative arrays representing parent attributes with their children.
     * @param mixed $price      An associative array with keys 'min' and 'max' representing the price range.
     */
    public function __construct(array $selected, array $attributes, $price)
    {
        $this->selected = $selected;

        // Convert each raw attribute data into a FilterAttributeParentModel instance.
        foreach ($attributes as $key => $attribute) {
            $attributes[$key] = new FilterAttributeParentModel(
                $attribute['id'],
                $attribute['name'],
                $attribute['filter_label'],
                $attribute['filter_type'],
                (bool)$attribute['show_in_full_search'],
                (bool)$attribute['show_in_recommendation'],
                $attribute['recommendation_title'],
                (bool)$attribute['is_option'],
                $attribute['children']
            );
        }
        $this->attributes = $attributes;

        // Initialize the price filter using the provided price range.
        $this->price = new FilterPriceModel($price['min'], $price['max']);
    }

    /**
     * Counts the total number of selected child attributes across all parent attributes.
     *
     * @return int The total count of selected child attributes.
     */
    public function countSelected(): int
    {
        return array_sum(array_map('count', $this->selected));
    }

    /**
     * Checks if a specific child attribute is selected under a given parent attribute.
     *
     * @param int $parent_id The ID of the parent attribute.
     * @param int $child_id  The ID of the child attribute to check.
     *
     * @return bool True if the child attribute is selected under the specified parent, false otherwise.
     */
    public function isSelected(int $parent_id, int $child_id): bool
    {
        return isset($this->selected[$parent_id]) && in_array($child_id, $this->selected[$parent_id], true);
    }
}

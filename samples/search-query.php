<?php
// Include the autoloader if needed
use AisearchClient\Aisearch;
use AisearchClient\controllers\Search\QueryAction;

// Initialize the SDK with your site ID and client token.
$siteId = 1234;
$clientToken = 'YOUR_CLIENT_TOKEN';

$sdk = new Aisearch($siteId, $clientToken);

// Get the search controller and create a new query action.
$queryAction = $sdk->search()->query();

// Set search parameters.
$queryAction->setUserId('user123')
    ->setQuery('laptop')
    ->setLimit(20)
    ->setPage(1)
    ->setSort(QueryAction::SORT_PRICE_ASC)
    ->setFilterMinPrice(500.00)
    ->setFilterMaxPrice(1500.00);

// Optionally, add filter attributes (e.g., parent attribute ID 10 with child attribute ID 101).
$queryAction->addFilterAttribute(10, 101);

// Execute the search query.
$result = $queryAction->get();

// Check and display the results.
if ($result->model !== null) {
    echo "Search Status: " . $result->model->status . "\n";
    echo "Total Products Found: " . $result->model->count . "\n";
    foreach ($result->model->products as $product) {
        echo "Product: " . $product->name . " - Price: " . $product->price . "\n";
    }
} else {
    echo "Search query failed.\n";
}

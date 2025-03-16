<?php

use AisearchClient\Aisearch;

$siteId = 1234;
$clientToken = 'YOUR_CLIENT_TOKEN';

// Initialize the SDK.
$sdk = new Aisearch($siteId, $clientToken);

// Get the recommendation controller and use the carousel action.
$recommendationController = $sdk->recommendation();
$carouselAction = $recommendationController->carousel();

// Set necessary parameters.
$carouselAction->setUserId('user123');
// Optionally, you can set more parameters like category or brand filters here.

// Execute the recommendation query.
$carouselAction->get();

// Display the recommendation results.
if ($carouselAction->model !== null) {
    echo "Is Recommendation Personalized? " . ($carouselAction->model->personalized ? 'Yes' : 'No') . "\n";
    foreach ($carouselAction->model->products as $product) {
        echo "Recommended Product: " . $product->name . "\n";
    }
} else {
    echo "Recommendation query failed.\n";
}

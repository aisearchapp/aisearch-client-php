<?php

use AisearchClient\Aisearch;

$siteId = 1234;
$clientToken = 'YOUR_CLIENT_TOKEN';

// Initialize the SDK.
$sdk = new Aisearch($siteId, $clientToken);

// Get the search recent query controller.
$searchRecentQueryController = $sdk->searchRecentQuery();

// Set the user ID and the query to be deleted.
$searchRecentQueryController->setUserId('user123');
$searchRecentQueryController->setQuery('laptop');

// Execute the delete action.
$deleteSuccess = $searchRecentQueryController->delete();

if ($deleteSuccess) {
    echo "Recent search query deleted successfully.\n";
} else {
    echo "Failed to delete recent search query.\n";
}

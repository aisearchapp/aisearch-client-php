<?php

use AisearchClient\Aisearch;

$siteId = 1234;
$clientToken = 'YOUR_CLIENT_TOKEN';

// Initialize the SDK.
$sdk = new Aisearch($siteId, $clientToken);

// Retrieve settings using the settings controller.
$settingsAction = $sdk->settings()->get();

// Display settings details.
if ($settingsAction->model !== null) {
    echo "Settings Status: " . ($settingsAction->model->status ? "Active" : "Inactive") . "\n";
    echo "Language: " . $settingsAction->model->language_id . "\n";

    // Display currencies information.
    foreach ($settingsAction->model->currencies as $currency) {
        echo "Currency: " . $currency->currency_code . " - Symbol: " . $currency->symbol . "\n";
    }

    // Display subscription settings.
    echo "Remove Branding: " . ($settingsAction->model->subscription->remove_branding ? 'Yes' : 'No') . "\n";
} else {
    echo "Failed to retrieve settings.\n";
}

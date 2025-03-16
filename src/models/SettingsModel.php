<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class SettingsModel
 *
 * This model encapsulates configuration settings for the Aisearch SDK.
 * It contains general settings such as the system status, language identifier,
 * CTA (Call-To-Action) settings, available currencies, and subscription details.
 *
 * The constructor processes raw currency data by converting each currency array
 * into a CurrencyModel instance.
 */
class SettingsModel
{
    /**
     * Indicates whether the settings are enabled (true) or disabled (false).
     *
     * @var bool
     */
    public bool $status;

    /**
     * The language identifier (e.g., "en", "tr") for the settings.
     *
     * @var string
     */
    public string $language_id;

    /**
     * The Call-To-Action (CTA) settings specific to the system.
     *
     * @var SettingsCtaModel
     */
    public SettingsCtaModel $cta;

    /**
     * An array of CurrencyModel instances representing available currencies.
     *
     * @var CurrencyModel[]
     */
    public array $currencies;

    /**
     * Subscription settings model.
     *
     * @var SettingsSubscriptionModel
     */
    public SettingsSubscriptionModel $subscription;

    /**
     * Constructor for SettingsModel.
     *
     * Processes the raw currency data and initializes the settings model with the provided values.
     *
     * @param bool $status The status of the settings.
     * @param string $language_id The language identifier.
     * @param SettingsCtaModel $cta The CTA settings.
     * @param array $currencies Raw currency data, each element is an associative array.
     * @param SettingsSubscriptionModel $subscription The subscription settings.
     */
    public function __construct(
        bool $status,
        string $language_id,
        SettingsCtaModel $cta,
        array $currencies,
        SettingsSubscriptionModel $subscription
    ) {
        // Process raw currency data: Convert each raw currency array into a CurrencyModel instance.
        foreach ($currencies as $key => $currency) {
            $currencies[$key] = new CurrencyModel(
                $currency['currency_code'],
                $currency['decimal_point'],
                $currency['thousands_separator'],
                $currency['symbol'],
                $currency['exchange_rate'],
                $currency['symbol_position'],
                (bool) $currency['remove_decimal_zero'],
                (bool)    $currency['is_active']
            );
        }

        // Initialize class properties with processed data.
        $this->status = $status;
        $this->language_id = $language_id;
        $this->cta = $cta;
        $this->currencies = $currencies;
        $this->subscription = $subscription;
    }
}

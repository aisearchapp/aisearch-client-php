<?php
declare(strict_types=1);

namespace AisearchClient\models;

/**
 * Class SettingsSubscriptionModel
 *
 * This model represents the subscription settings for the Aisearch SDK.
 * It currently encapsulates a single setting that determines whether branding should be removed.
 */
class SettingsSubscriptionModel
{
    /**
     * Flag indicating whether branding should be removed from the service.
     *
     * @var bool
     */
    public bool $remove_branding;

    /**
     * Constructor for SettingsSubscriptionModel.
     *
     * Initializes the subscription model with the provided branding removal setting.
     *
     * @param bool $remove_branding If true, branding is removed; otherwise, branding is displayed.
     */
    public function __construct(bool $remove_branding)
    {
        $this->remove_branding = $remove_branding;
    }
}

<?php
declare(strict_types=1);

namespace AisearchClient\controllers;

use AisearchClient\Controller;
use AisearchClient\controllers\Settings\IndexAction;

/**
 * Class SettingsController
 *
 * This controller handles settings-related operations for the Aisearch API.
 * It provides a method to retrieve the settings configuration via the IndexAction.
 */
class SettingsController extends Controller
{
    /**
     * Retrieves the settings.
     *
     * This method instantiates an IndexAction object using the current controller instance,
     * executes its get() method to fetch the settings, and returns the IndexAction instance.
     *
     * @return IndexAction The action instance after retrieving the settings.
     */
    public function get(): IndexAction
    {
        return (new IndexAction($this))->get();
    }
}

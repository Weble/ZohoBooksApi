<?php

namespace Webleit\ZohoBooksApi\Modules\Settings;

use Webleit\ZohoBooksApi\Modules\Module;
use Webleit\ZohoBooksApi\Modules\Settings\Currencies\ExchangeRates;

/**
 * Class Preferences
 * @package Webleit\ZohoBooksApi\Modules
 */
class Currencies extends Module
{
    /**
     * @param $id
     * @return ExchangeRates
     */
    public function getExchangeRates($id)
    {
        $rates = new ExchangeRates($this->client);
        $rates->setParent($this->get($id));

        return $rates;
    }

    /**
     * @return string
     */
    public function getUrlPath()
    {
        return 'settings/currencies';
    }

    /**
     * @return string
     */
    public function getModelClassName()
    {
        return '\\Webleit\\ZohoBooksApi\\Models\\Settings\\Currency';
    }
}
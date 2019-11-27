<?php

namespace Webleit\ZohoBooksApi\Modules\Settings\Currencies;

use Webleit\ZohoBooksApi\Models\Settings\Currency\ExchangeRate;
use Webleit\ZohoBooksApi\Modules\SubModule;

/**
 * Class ExchangeRates
 * @package Webleit\ZohoBooksApi\Modules\Settings\Currencies
 *
 * @method ExchangeRate get($id)
 */
class ExchangeRates extends SubModule
{
    /**
     * @return string
     */
    public function getModelClassName()
    {
        return '\Webleit\\ZohoBooksApi\\Models\\Settings\\Currency\\ExchangeRate';
    }

    /**
     * @return string
     */
    public function getResourceKey()
    {
        return 'exchange_rates';
    }
}

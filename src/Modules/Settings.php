<?php

namespace Webleit\ZohoBooksApi\Modules;

use Webleit\ZohoBooksApi\Client;
use Webleit\ZohoBooksApi\Mixins\ProvidesModules;
use Webleit\ZohoBooksApi\Modules\Settings as SettingsModules;

/**
 * Class Settings
 * @package Webleit\ZohoBooksApi\Modules
 *
 * @property-read SettingsModules\Invoices $invoices;
 * @property-read SettingsModules\Estimates $estimates;
 * @property-read SettingsModules\CreditNotes $creditnotes;
 * @property-read SettingsModules\Preferences $preferences;
 * @property-read SettingsModules\Currencies $currencies;
 * @property-read SettingsModules\Taxes $taxes;
 * @property-read SettingsModules\TaxGroups $taxgroups;
 * @property-read SettingsModules\TaxAuthorities $taxauthorities;
 * @property-read SettingsModules\TaxExemptions $taxexemptions;
 * @property-read SettingsModules\OpeningBalances $openingbalances;
 * @property-read SettingsModules\AutoReminders $autoreminders;
 * @property-read SettingsModules\ManualReminders $manualreminders;
 */
class Settings
{
    use ProvidesModules;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var array
     */
    protected $availableModules = [
        'invoices',
        'preferences',
        'estimates',
        'creditnotes',
        'items',
        'currencies',
        'taxes',
        'taxgroups',
        'taxauthorities',
        'taxexemptions',
        'openingbalances',
        'autoreminders',
        'manualreminders'
    ];

    /**
     * @var string
     */
    protected $modulesNamespace = '\\Webleit\\ZohoBooksApi\\Modules\\Settings\\';

    /**
     * Settings constructor.
     * @param Client $client
     */
    function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Proxy any module call to the right api call
     * @param $name
     * @return Module
     */
    public function __get($name)
    {
        return $this->createModule($name);
    }
}
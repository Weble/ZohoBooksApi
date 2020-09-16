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
class Settings implements \Webleit\ZohoBooksApi\Contracts\ProvidesModules, \Webleit\ZohoBooksApi\Contracts\Module
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
        'invoices' => Settings\Invoices::class,
        'preferences' => Settings\Preferences::class,
        'estimates' => Settings\Estimates::class,
        'creditnotes' => Settings\CreditNotes::class,
        'currencies' => Settings\Currencies::class,
        'taxes' => Settings\Taxes::class,
        'taxgroups' => Settings\TaxGroups::class,
        'taxauthorities' => Settings\TaxAuthorities::class,
        'taxexemptions' => Settings\TaxExemptions::class,
        'openingbalances' => Settings\OpeningBalances::class,
        'autoreminders' => Settings\AutoReminders::class,
        'manualreminders' => Settings\ManualReminders::class
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

    public function getClient(): Client
    {
        return $this->client;
    }
}

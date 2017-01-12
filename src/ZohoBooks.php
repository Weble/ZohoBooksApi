<?php

namespace Webleit\ZohoBooksApi;

use Doctrine\Common\Inflector\Inflector;
use Webleit\ZohoBooksApi\Modules\Contacts;
use Webleit\ZohoBooksApi\Modules\Module;
use Webleit\ZohoBooksApi\Modules\Organizations;

/**
 * Class ZohoBooks
 * @package Webleit\ZohoBooksApi
 *
 * @property-read Contacts $contacts
 * @property-read Module $estimates
 * @property-read Module $salesorders
 * @property-read Invoices $invoices
 * @property-read Module $recurringinvoices
 * @property-read Module $creditnotes
 * @property-read Module $customerpayments
 * @property-read Module $expenses
 * @property-read Module $recurringexpenses
 * @property-read Module $purchaseorders
 * @property-read Module $bills
 * @property-read Module $vendorcredits
 * @property-read Module $vendorpayments
 * @property-read Module $bankaccounts
 * @property-read Module $banktransactions
 * @property-read Module $bankrules
 * @property-read Module $chartofaccounts
 * @property-read Module $journals
 * @property-read Module $basecurrencyadjustment
 * @property-read Module $projects
 * @property-read Module $settings
 * @property-read Organizations $organizations
 */
class ZohoBooks
{
    /**
     * Zoho Books Api Auth Token
     * @var string
     */
    protected $authToken = '';

    /**
     * Zoho Books Organization Id
     * @var string
     */
    protected $organizationId = '';

    /**
     * Guzzle client to deal with the request
     * @var Client
     */
    protected $client;

    /**
     * Stored locally the total number per resource type
     * @var array
     */
    protected $totals = [];

    /**
     * List of available Zoho Books Api endpoints (see https://www.zoho.com/books/api/v3)
     * @var array
     */
    protected $availableModules = [
        'contacts',
        'estimates',
        'salesorders',
        'invoices',
        'recurringinvoices',
        'creditnotes',
        'customerpayments',
        'expenses',
        'recurringexpenses',
        'purchaseorders',
        'bills',
        'vendorcredits',
        'vendorpayments',
        'bankaccounts',
        'banktransactions',
        'bankrules',
        'chartofaccounts',
        'journals',
        'basecurrencyadjustment',
        'projects',
        'settings',
        'organizations'
    ];

    /**
     * ZohoBooksApi constructor.
     * @param $authToken    Zoho Books Api Token (See https://www.zoho.com/books/api/v3/)
     * @param string $organizationId The organization id you want to deal with (See https://www.zoho.com/books/api/v3/)
     */
    public function __construct($authToken, $organizationId = null)
    {
        $this->client = new Client($authToken);

        // Set the default organization id to the default org in zoho books
        if (!$organizationId) {
            $organizationId = $this->organizations->getDefaultOrganizationId();
        }

        $this->organizationId = $organizationId;
        $this->client->setOrganizationId($organizationId);
    }

    /**
     * Proxy any module call to the right api call
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        $module = $this->getModuleName($name);

        if (in_array($module, $this->availableModules)) {
            $class =  '\\Webleit\\ZohoBooksApi\\Modules\\' . ucfirst((Inflector::camelize($module)));
            return new $class($this->client);
        }
    }

    /**
     * Get the list of available modules
     * @return array
     */
    public function getAvailableModules()
    {
        return $this->availableModules;
    }

    /**
     * Get the module name by joining any camelcase module name and trying plural vs singular
     * @param $moduleName
     * @return string
     */
    protected function getModuleName($moduleName)
    {
        // Try also singular
        $module = $moduleName;
        if (in_array($moduleName, $this->availableModules)) {
            $module = $moduleName;
        } else {
            $moduleName = Inflector::pluralize($moduleName);

            if (in_array($moduleName, $this->availableModules)) {
                $module = $moduleName;
            }
        }

        return $module;
    }
}
<?php

namespace Webleit\ZohoBooksApi;

use Webleit\ZohoBooksApi\Mixins\ProvidesModules;
use Webleit\ZohoBooksApi\Modules\Contacts;
use Webleit\ZohoBooksApi\Modules;

/**
 * Class ZohoBooks
 * @package Webleit\ZohoBooksApi
 *
 * @property-read Contacts $contacts
 * @property-read Modules\Module $estimates
 * @property-read Modules\SalesOrders $salesorders
 * @property-read Modules\Invoices $invoices
 * @property-read Modules\RecurringInvoices $recurringinvoices
 * @property-read Modules\CreditNotes $creditnotes
 * @property-read Modules\CustomerPayments $customerpayments
 * @property-read Modules\Expenses $expenses
 * @property-read Modules\RecurringExpenses $recurringexpenses
 * @property-read Modules\PurchaseOrders $purchaseorders
 * @property-read Modules\Bills $bills
 * @property-read Modules\VendorCredits $vendorcredits
 * @property-read Modules\VendorPayments $vendorpayments
 * @property-read Modules\BankAccounts $bankaccounts
 * @property-read Modules\BankTransactions $banktransactions
 * @property-read Modules\BankRules $bankrules
 * @property-read Modules\ChartOfAccounts $chartofaccounts
 * @property-read Modules\Journals $journals
 * @property-read Modules\BaseCurrencyAdjustment $basecurrencyadjustment
 * @property-read Modules\Projects $projects
 * @property-read Modules\Settings $settings
 * @property-read Modules\Organizations $organizations
 * @property-read Modules\Items $items;
 */
class ZohoBooks
{
    use ProvidesModules;

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
     * @var string
     */
    protected $modulesNamespace = '\\Webleit\\ZohoBooksApi\\Modules\\';

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
        'organizations',
        'items'
    ];

    /**
     * ZohoBooksApi constructor.
     * @param string $authToken         Zoho Books Api Token (See https://www.zoho.com/books/api/v3/)
     * @param string $organizationId    The organization id you want to deal with (See https://www.zoho.com/books/api/v3/)
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
     * @return Modules\Module
     */
    public function __get($name)
    {
        return $this->createModule($name);
    }
}
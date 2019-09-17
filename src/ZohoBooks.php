<?php

namespace Webleit\ZohoBooksApi;

use Webleit\ZohoBooksApi\Mixins\ProvidesModules;
use Webleit\ZohoBooksApi\Modules;

/**
 * Class ZohoBooks
 * @package Webleit\ZohoBooksApi
 *
 * @property-read Modules\Contacts $contacts
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
class ZohoBooks implements \Webleit\ZohoBooksApi\Contracts\ProvidesModules
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
        'contacts' => Modules\Contacts::class,
        'estimates' => Modules\Estimates::class,
        'salesorders' => Modules\SalesOrders::class,
        'invoices' => Modules\Invoices::class,
        'recurringinvoices' => Modules\RecurringInvoices::class,
        'creditnotes' => Modules\CreditNotes::class,
        'customerpayments' => Modules\CustomerPayments::class,
        'expenses' => Modules\Expenses::class,
        'recurringexpenses' => Modules\RecurringExpenses::class,
        'purchaseorders' => Modules\PurchaseOrders::class,
        'bills' => Modules\Bills::class,
        'vendorcredits' => Modules\VendorCredits::class,
        'vendorpayments' => Modules\VendorPayments::class,
        'bankaccounts' => Modules\BankAccounts::class,
        'banktransactions' => Modules\BankTransactions::class,
        'bankrules' => Modules\BankRules::class,
        'chartofaccounts' => Modules\ChartOfAccounts::class,
        'journals' => Modules\Journals::class,
        'basecurrencyadjustment' => Modules\BaseCurrencyAdjustment::class,
        'projects' => Modules\Projects::class,
        'settings' => Modules\Settings::class,
        'organizations' => Modules\Organizations::class,
        'items' => Modules\Items::class
    ];

    /**
     * ZohoBooksApi constructor.
     * @param string $authToken         Zoho Books Api Token (See https://www.zoho.com/books/api/v3/)
     * @param string $organizationId    The organization id you want to deal with (See https://www.zoho.com/books/api/v3/)
     * @param string $region            The API Region. Can be US or EU
     */
    public function __construct($clientId, $clientSecret, $refreshToken = '')
    {
        $this->client = new Client($clientId, $clientSecret, $refreshToken);
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

    /**
     * Get Client Class constructor.
     * @return \Client|object class
     */
    public function getClient()
    {
        return $this->client;
    }

    public function setOrganizationId($id)
    {
        $this->client->setOrganizationId($id);
        return $this;
    }
    
}
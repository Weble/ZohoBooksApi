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
 * @property-read Modules\Items $items
 * @property-read Modules\Users $users
 * @property-read Modules\Import $import
 */
class ZohoBooks implements Contracts\ProvidesModules
{
    use ProvidesModules;

    /**
     * Guzzle client to deal with the request
     * @var Client
     */
    protected $client;

    /**
     * List of available Zoho Books Api endpoints (see https://www.zoho.com/books/api/v3)
     * @var array
     */
    protected $availableModules = [
        'contacts'               => Modules\Contacts::class,
        'estimates'              => Modules\Estimates::class,
        'salesorders'            => Modules\SalesOrders::class,
        'invoices'               => Modules\Invoices::class,
        'recurringinvoices'      => Modules\RecurringInvoices::class,
        'creditnotes'            => Modules\CreditNotes::class,
        'customerpayments'       => Modules\CustomerPayments::class,
        'expenses'               => Modules\Expenses::class,
        'recurringexpenses'      => Modules\RecurringExpenses::class,
        'purchaseorders'         => Modules\PurchaseOrders::class,
        'bills'                  => Modules\Bills::class,
        'vendorcredits'          => Modules\VendorCredits::class,
        'vendorpayments'         => Modules\VendorPayments::class,
        'bankaccounts'           => Modules\BankAccounts::class,
        'banktransactions'       => Modules\BankTransactions::class,
        'bankrules'              => Modules\BankRules::class,
        'chartofaccounts'        => Modules\ChartOfAccounts::class,
        'journals'               => Modules\Journals::class,
        'basecurrencyadjustment' => Modules\BaseCurrencyAdjustment::class,
        'projects'               => Modules\Projects::class,
        'settings'               => Modules\Settings::class,
        'organizations'          => Modules\Organizations::class,
        'items'                  => Modules\Items::class,
        'users'                  => Modules\Users::class,
        'import'                 => Modules\Import::class,
    ];

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function __get(string $name): Contracts\Module
    {
        return $this->createModule($name);
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}

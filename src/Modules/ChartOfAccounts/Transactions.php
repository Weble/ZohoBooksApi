<?php

namespace Webleit\ZohoBooksApi\Modules\ChartOfAccounts;

use Webleit\ZohoBooksApi\Models\ChartOfAccount;
use Webleit\ZohoBooksApi\Modules\Module;

/**
 * Class Transactions
 * @package Webleit\ZohoBooksApi\Modules\Transactions
 *
 * @method ChartOfAccount\Transaction get($id)
 */
class Transactions extends Module
{
    protected $urlPath = 'chartofaccounts/transactions';

    /**
     * @return string
     */
    public function getModelClassName()
    {
        return  '\\Webleit\\ZohoBooksApi\\Models\\ChartOfAccount\\Transaction';
    }
}
<?php

namespace Webleit\ZohoBooksApi\Modules;
use Webleit\ZohoBooksApi\Client;

/**
 * Class ChartOfAccounts
 * @package Webleit\ZohoBooksApi\Modules
 */
class ChartOfAccounts extends Module
{

    /**
     * Return Zoho API Key Name for Chart of Accounts
     */
    public function getApiKeyName()
    {
        return 'account_id';
    }
    
    /**
     * @param $id
     * @return bool
     */
    public function markAsActive($id)
    {
        return $this->markAs('active', $id);
    }

    /**
     * @param $id
     * @return bool
     */
    public function markAsInactive($id)
    {
        return $this->markAs('inactive', $id);
    }
}

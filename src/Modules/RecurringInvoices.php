<?php

namespace Webleit\ZohoBooksApi\Modules;

/**
 * Class RecurringInvoices
 * @package Webleit\ZohoBooksApi\Modules
 */
class RecurringInvoices extends Documents
{
    /**
     * @param $id string
     * @return boolean
     */
    public function stop($id)
    {
        return $this->markAs($id, 'stop');
    }

    /**
     * @param $id string
     * @return boolean
     */
    public function resume($id)
    {
        return $this->markAs($id, 'resume');
    }

    /**
     * Override returned key
     *
     * This overrides the key that is returned from zoho, as they
     * send back 'recurring_invoices' instead of 'recurringinvoices'
     *
     * @return string
     */
    public function getResourceKey()
    {
        return 'recurring_invoices';
    }
}

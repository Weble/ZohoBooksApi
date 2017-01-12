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
}
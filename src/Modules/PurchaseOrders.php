<?php

namespace Webleit\ZohoBooksApi\Modules;

/**
 * Class PurchaseOrders
 * @package Webleit\ZohoBooksApi\Modules
 */
class PurchaseOrders extends Documents
{
    /**
     * @param $id string
     * @return boolean
     */
    public function markAsOpen($id)
    {
        return $this->markAs($id, 'open');
    }

    /**
     * @param $id string
     * @return boolean
     */
    public function markAsBilled($id)
    {
        return $this->markAs($id, 'billed');
    }

    /**
     * @param $id string
     * @return boolean
     */
    public function cancel($id)
    {
        return $this->markAs($id, 'cancelled');
    }
}
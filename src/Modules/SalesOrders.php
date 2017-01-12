<?php

namespace Webleit\ZohoBooksApi\Modules;

/**
 * Class SalesOrders
 * @package Webleit\ZohoBooksApi\Modules
 */
class SalesOrders extends Documents
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
    public function markAsVoid($id)
    {
        return $this->markAs($id, 'void');
    }
}
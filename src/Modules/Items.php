<?php

namespace Webleit\ZohoBooksApi\Modules;

/**
 * Class items
 * @package Webleit\ZohoBooksApi\Modules
 */
class Items extends Module
{
    /**
     * @param $id
     * @return bool
     */
    public function markAsActive($id)
    {
        return $this->doAction($id, 'active');
    }

    /**
     * @param $id
     * @return bool
     */
    public function markAsInactive($id)
    {
        return $this->doAction($id, 'inactive');
    }
}
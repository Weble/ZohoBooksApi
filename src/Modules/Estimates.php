<?php

namespace Webleit\ZohoBooksApi\Modules;

/**
 * Class Estimates
 * @package Webleit\ZohoBooksApi\Modules
 */
class Estimates extends Documents
{
    /**
     * @param $id string
     * @return boolean
     */
    public function markAsDeclined($id)
    {
        return $this->markAs($id, 'declined');
    }

    /**
     * @param $id string
     * @return boolean
     */
    public function markAsAccepted($id)
    {
        return $this->markAs($id, 'accepted');
    }
}
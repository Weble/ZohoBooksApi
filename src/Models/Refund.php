<?php

namespace Webleit\ZohoBooksApi\Models;

/**
 * Class Refund
 * @package Webleit\ZohoBooksApi\Models
 */
class Refund extends Model
{
    public function getKeyName()
    {
        return 'creditnote_refund_id';
    }
}
<?php

namespace Webleit\ZohoBooksApi\Models\CustomerPayment;

use Webleit\ZohoBooksApi\Models\Model;

/**
 * Class Refund
 * @package Webleit\ZohoBooksApi\Models\CustomerPayment
 */
class Refund extends Model
{
    public function getKeyName()
    {
        return 'payment_refund_id';
    }
}
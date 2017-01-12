<?php

namespace Webleit\ZohoBooksApi\Models\VendorPayment;

use Webleit\ZohoBooksApi\Models\Model;

/**
 * Class Refund
 * @package Webleit\ZohoBooksApi\Models\VendorPayment
 */
class Refund extends Model
{
    public function getKeyName()
    {
        return 'vendorpayment_refund_id';
    }
}
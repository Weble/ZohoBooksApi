<?php

namespace Webleit\ZohoBooksApi\Models\VendorCredit;

use Webleit\ZohoBooksApi\Models\Model;

/**
 * Class Refund
 * @package Webleit\ZohoBooksApi\Models\VendorCredit
 */
class Refund extends Model
{
    public function getKeyName()
    {
        return 'vendor_credit_refund_id';
    }
}
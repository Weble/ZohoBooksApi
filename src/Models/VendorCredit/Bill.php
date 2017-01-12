<?php

namespace Webleit\ZohoBooksApi\Models\VendorCredit;

use Webleit\ZohoBooksApi\Models\Model;

/**
 * Class Payment
 * @package Webleit\ZohoBooksApi\Models\VendorCredit
 */
class Bill extends Model
{
    public function getKeyName()
    {
        return 'vendor_credit_bill_id';
    }
}
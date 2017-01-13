<?php

namespace Webleit\ZohoBooksApi\Modules\VendorPayments;

use Webleit\ZohoBooksApi\Modules\Module;
use Webleit\ZohoBooksApi\Models\VendorPayment;

/**
 * Class Refunds
 * @package Webleit\ZohoBooksApi\Modules\VendorPayments
 *
 * @method VendorPayment\Refund get($id)
 */
class Refunds extends Module
{
    /**
     * @return string
     */
    public function getModelClassName()
    {
        return  '\\Webleit\\ZohoBooksApi\\Models\\VendorPayment\\Refund';
    }
}
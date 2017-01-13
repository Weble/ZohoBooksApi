<?php

namespace Webleit\ZohoBooksApi\Modules\CustomerPayments;

use Webleit\ZohoBooksApi\Modules\Module;
use Webleit\ZohoBooksApi\Models\CustomerPayment;

/**
 * Class Refunds
 * @package Webleit\ZohoBooksApi\Modules\CustomerPayments
 *
 * @method CustomerPayment\Refund get($id)
 */
class Refunds extends Module
{
    /**
     * @return string
     */
    public function getModelClassName()
    {
        return  '\\Webleit\\ZohoBooksApi\\Models\\CustomerPayment\\Refund';
    }
}
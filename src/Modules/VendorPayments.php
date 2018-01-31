<?php

namespace Webleit\ZohoBooksApi\Modules;

use Webleit\ZohoBooksApi\Client;
use Webleit\ZohoBooksApi\Modules\VendorPayments\Refunds;
use Webleit\ZohoBooksApi\ZohoBooks;

/**
 * Class VendorPayments
 * @package Webleit\ZohoBooksApi\Modules
 */
class VendorPayments extends Module
{
    /**
     * @var Refunds
     */
    public $refunds;

    protected $urlPath = 'vendorpayments';

    public function __construct(Client $client, ZohoBooks $zohoBook)
    {
        parent::__construct($client, $zohoBook);

        $this->refunds = new Refunds($client, $zohoBook);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'payment';
    }

    /**
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function getRefunds($id)
    {
        $className = $this->getModelClassName() . '\\Refund';
        return $this->getPropertyList('refunds', $id, $className, 'vendorpayment_refunds', $this->refunds);
    }
}
<?php

namespace Webleit\ZohoBooksApi\Modules;

use Webleit\ZohoBooksApi\Client;
use Webleit\ZohoBooksApi\Modules\CustomerPayments\Refunds;
use Webleit\ZohoBooksApi\ZohoBooks;

/**
 * Class CustomerPayments
 * @package Webleit\ZohoBooksApi\Modules
 */
class CustomerPayments extends Module
{
    /**
     * @var Refunds
     */
    public $refunds;

    protected $urlPath = 'customerpayments';

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
        return $this->getPropertyList('refunds', $id, $className, 'payment_refunds', $this->refunds);
    }
}
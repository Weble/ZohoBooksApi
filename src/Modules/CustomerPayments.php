<?php

namespace Webleit\ZohoBooksApi\Modules;

use Webleit\ZohoBooksApi\Client;
use Webleit\ZohoBooksApi\Modules\CustomerPayments\Refunds;

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

    public function __construct(Client $client)
    {
        parent::__construct($client);

        $this->refunds = new Refunds($client);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'payment';
    }

    /**
     * @return string
     */
    public function getResourceKey()
    {
        return 'customerpayments';
    }

    /**
     * @return string
     */
    public function getResourceItemKey()
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

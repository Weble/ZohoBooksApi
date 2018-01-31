<?php

namespace Webleit\ZohoBooksApi\Modules;
use Webleit\ZohoBooksApi\Client;
use Webleit\ZohoBooksApi\Modules\Contacts\ContactPersons;
use Webleit\ZohoBooksApi\ZohoBooks;

/**
 * Class ChartOfAccounts
 * @package Webleit\ZohoBooksApi\Modules
 */
class ChartOfAccounts extends Module
{
    /**
     * @var ContactPersons
     */
    public $contactpersons;

    public function __construct(Client $client, ZohoBooks $zohoBook)
    {
        parent::__construct($client, $zohoBook);

        $this->contactpersons = new ContactPersons($client, $zohoBook);
    }

    /**
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function getContactPersons($id)
    {
        $className = $this->getModelClassName() . '\\Person';
        return $this->getPropertyList('contactpersons', $id, $className, 'contact_persons', $this->contactpersons);
    }

    /**
     * @param $id
     * @return bool
     */
    public function markAsActive($id)
    {
        return $this->markAs('active', $id);
    }

    /**
     * @param $id
     * @return bool
     */
    public function markAsInactive($id)
    {
        return $this->markAs('inactive', $id);
    }
}
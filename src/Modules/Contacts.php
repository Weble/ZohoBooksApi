<?php

namespace Webleit\ZohoBooksApi\Modules;

use Webleit\ZohoBooksApi\Client;
use Webleit\ZohoBooksApi\Modules\Contacts\Addresses;
use Webleit\ZohoBooksApi\Modules\Contacts\ContactPersons;
use Webleit\ZohoBooksApi\Modules\Mixins\Commentable;
use Webleit\ZohoBooksApi\Models\Contact;
use Webleit\ZohoBooksApi\Modules\Mixins\Refundable;

/**
 * Class Contacts
 * @package Webleit\ZohoBooksApi\Modules
 *
 * @method Contact get($id)
 */
class Contacts extends Module
{
    use Commentable, Refundable;

    /**
     * @var ContactPersons
     */
    public $contactpersons;

    /**
     * @var Addresses
     */
    public $addresses;

    public function __construct(Client $client)
    {
        parent::__construct($client);

        $this->contactpersons = new ContactPersons($client);
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
     * @return \Illuminate\Support\Collection
     */
    public function getAddresses($id)
    {
        $className = $this->getModelClassName() . '\\Address';
        return $this->getPropertyList('address', $id, $className, 'addresses', $this->addresses);
    }

    /**
     * @param string $id
     * @param array $from
     * @param array $to
     * @param array $data
     * @return bool
     */
    public function emailStatement($id, $from = null, $to = null, $data = [])
    {
        $params = [];

        if ($from && !($from instanceof \DateTime)) {
            $from = new \DateTime($from);
            $params['start_date'] = $from->format('Y-m-d');
        }

        if ($to && !($to instanceof \DateTime)) {
            $to = new \DateTime($to);
            $params['end_date'] = $to->format('Y-m-d');
        }

        $this->client->post($this->getUrl() . '/' . $id . '/statements/email', $data, $params);
        // If we arrive here without exceptions, everything went well
        return true;
    }

    /**
     * @param $id
     * @return array
     */
    public function getStatementEmailContent($id, $from = null, $to = null)
    {
        $params = [];

        if ($from && !($from instanceof \DateTime)) {
            $from = new \DateTime($from);
            $params['start_date'] = $from->format('Y-m-d');
        }

        if ($to && !($to instanceof \DateTime)) {
            $to = new \DateTime($to);
            $params['end_date'] = $to->format('Y-m-d');
        }

        return $this->client->get($this->getUrl() . '/' . $id . '/statements/email', null, $params);
    }

    /**
     * @param $id
     * @param array $data
     * @param array $params
     * @return bool;
     */
    public function email($id, $data = [], $params = [])
    {
        return $this->doAction($id, 'email', $data, $params);
    }

    /**
     * @param $id string
     * @return boolean
     */
    public function markAsActive($id)
    {
        return $this->markAs($id, 'active');
    }

    /**
     * @param $id string
     * @return boolean
     */
    public function markAsInactive($id)
    {
        return $this->markAs($id, 'inactive');
    }

    /**
     * @param $id string
     * @return boolean
     */
    public function enablePaymentReminders($id)
    {
        return $this->markAs($id, 'enable', 'paymentreminders');
    }

    /**
     * @param $id string
     * @return boolean
     */
    public function disablePaymentReminders($id)
    {
        return $this->markAs($id, 'disable', 'paymentreminders');
    }

    /**
     * @param $id string
     * @return boolean
     */
    public function track1099($id)
    {
        return $this->doAction($id, 'track1099');
    }

    /**
     * @param $id string
     * @return boolean
     */
    public function untrack1099($id)
    {
        return $this->doAction($id, 'untrack1099');
    }
}

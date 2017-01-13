<?php

namespace Webleit\ZohoBooksApi\Modules\Contacts;

use Webleit\ZohoBooksApi\Models\Contact;
use Webleit\ZohoBooksApi\Modules\Module;

/**
 * Class Contacts
 * @package Webleit\ZohoBooksApi\Modules\Contacts
 *
 * @method Contact\Person get($id)
 */
class ContactPersons extends Module
{
    protected $urlPath = 'contacts/contactpersons';

    /**
     * @return string
     */
    public function getModelClassName()
    {
        return  '\\Webleit\\ZohoBooksApi\\Models\\Contact\\Person';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'contact_person';
    }

    /**
     * @param $id string
     * @return boolean
     */
    public function markAsPrimary($id)
    {
        $this->client->post($this->getUrl() . '/' . $id . '/primary');

        // If we arrive here without exceptions, everything went well
        return true;
    }
}
<?php

namespace Webleit\ZohoBooksApi\Models\Contact;

use Webleit\ZohoBooksApi\Models\Model;

/**
 * Class Person
 * @package Webleit\ZohoBooksApi\Models\Contact
 */
class Address extends Model
{
    public function getKeyName()
    {
        return 'address_id';
    }
}
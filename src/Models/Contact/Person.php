<?php

namespace Webleit\ZohoBooksApi\Models\Contact;

use Webleit\ZohoBooksApi\Models\Model;

/**
 * Class Person
 * @package Webleit\ZohoBooksApi\Models\Contact
 *
 * @method  bool markAsPrimary()
 */
class Person extends Model
{
    public function getKeyName()
    {
        return 'contact_person_id';
    }
}
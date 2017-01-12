<?php

namespace Webleit\ZohoBooksApi\Models;

use Illuminate\Support\Collection;

/**
 * Class Contact
 * @package Webleit\ZohoBooksApi\Models
 *
 * @method bool markAsActive()
 * @method bool markAsInactive()
 * @method bool enablePaymentReminders()
 * @method bool disablePaymentReminders()
 * @method bool track1099()
 * @method bool untrack1099()
 * @method bool emailStatement($from, $to, $data)
 * @method bool getStatementEmailContent($from, $to, $data)
 * @method Collection getComments()
 * @method Collection getContactPersons()
 */
class Contact extends Model
{

}
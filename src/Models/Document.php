<?php

namespace Webleit\ZohoBooksApi\Models;

use Illuminate\Support\Collection;

/**
 * Class Invoice
 * @package Webleit\ZohoBooksApi\Models
 *
 * @method bool markAsSent()
 * @method bool email($data, $params)
 * @method bool getEmailContent($params)
 * @method bool updateBillingAddress($data)
 * @method bool updateShippingAddress($data)
 */
abstract class Document extends Model
{

}
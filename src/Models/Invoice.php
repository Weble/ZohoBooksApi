<?php

namespace Webleit\ZohoBooksApi\Models;

use Illuminate\Support\Collection;

/**
 * Class Invoice
 * @package Webleit\ZohoBooksApi\Models
 *
 * @method bool writeOff()
 * @method bool cancelWriteOff()
 * @method bool markAsSent()
 * @method bool markAsDraft()
 * @method bool void()
 * @method bool email($data, $params)
 * @method bool sendPaymentReminder($data, $params)
 * @method bool getEmailContent($params)
 * @method bool getPaymentReminderEmailContent()
 * @method bool disablePaymentReminder()
 * @method bool enablePaymentReminder()
 * @method bool updateBillingAddress($data)
 * @method bool updateShippingAddress($data)
 * @method bool updateTemplate($idOrTemplate)
 * @method Collection getPayments()
 * @method Collection getCreditsApplied()
 * @method array applyCredits($data)
 * @method bool deletePayment($idOrPayment)
 * @method bool deleteAppliedCredit($idOrCredit)
 */
class Invoice extends Model
{

}
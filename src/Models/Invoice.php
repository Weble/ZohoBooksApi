<?php

namespace Webleit\ZohoBooksApi\Models;

use Illuminate\Support\Collection;

/**
 * Class Invoice
 * @package Webleit\ZohoBooksApi\Models
 *
 * @method bool writeOff()
 * @method bool cancelWriteOff()
 * @method bool markAsDraft()
 * @method bool void()
 * @method bool sendPaymentReminder($data, $params)
 * @method bool getPaymentReminderEmailContent()
 * @method bool disablePaymentReminder()
 * @method bool enablePaymentReminder()
 * @method bool updateTemplate($idOrTemplate)
 * @method Collection getPayments()
 * @method Collection getCreditsApplied()
 * @method array applyCredits($data)
 * @method bool deletePayment($idOrPayment)
 * @method bool deleteAppliedCredit($idOrCredit)
 */
class Invoice extends Document
{

}
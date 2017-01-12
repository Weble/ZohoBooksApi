<?php

namespace Webleit\ZohoBooksApi\Models\CreditNote;

use Webleit\ZohoBooksApi\Models\Model;

/**
 * Class Invoice
 * @package Webleit\ZohoBooksApi\Models\CreditNote
 */
class Invoice extends Model
{
    public function getKeyName()
    {
        return 'creditnote_invoice_id';
    }
}
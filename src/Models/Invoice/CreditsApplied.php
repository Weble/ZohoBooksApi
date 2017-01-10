<?php

namespace Webleit\ZohoBooksApi\Models\Invoice;

use Webleit\ZohoBooksApi\Models\Model;

/**
 * Class CreditsApplied
 * @package Webleit\ZohoBooksApi\Models\Invoice
 */
class CreditsApplied extends Model
{
    public function getKeyName()
    {
        return 'creditnote_id';
    }
}
<?php

namespace Webleit\ZohoBooksApi\Models;

class CustomerPayment extends Model
{
    public function getKeyName()
    {
        return 'payment_id';
    }
}
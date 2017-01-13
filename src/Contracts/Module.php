<?php

namespace Webleit\ZohoBooksApi\Contracts;

use Webleit\ZohoBooksApi\Client;

interface Module
{
    /**
     * @return Client
     */
    public function getClient();
}
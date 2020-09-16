<?php

namespace Webleit\ZohoBooksApi\Contracts;

use Webleit\ZohoBooksApi\Client;

interface ProvidesModules
{
    /**
     * Proxy any module call to the right api call
     * @param $name
     * @return mixed
     */
    public function createModule($name);

    /**
     * Get the list of available modules
     * @return array
     */
    public function getAvailableModules();

    /**
     * Get the list of available modules
     * @return array
     */
    public function getAvailableModuleKeys();
}

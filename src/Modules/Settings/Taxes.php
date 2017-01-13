<?php

namespace Webleit\ZohoBooksApi\Modules\Settings;

use Webleit\ZohoBooksApi\Modules\Module;

/**
 * Class Taxes
 * @package Webleit\ZohoBooksApi\Modules
 */
class Taxes extends Module
{
    /**
     * @return string
     */
    public function getUrlPath()
    {
        return 'settings/taxes';
    }

    /**
     * @return string
     */
    public function getModelClassName()
    {
        return '\\Webleit\\ZohoBooksApi\\Models\\Settings\\Tax';
    }
}
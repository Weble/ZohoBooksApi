<?php

namespace Webleit\ZohoBooksApi\Modules\Settings;

use Webleit\ZohoBooksApi\Modules\Module;

/**
 * Class TaxAuthorities
 * @package Webleit\ZohoBooksApi\Modules
 */
class TaxAuthorities extends Module
{
    /**
     * @return string
     */
    public function getUrlPath()
    {
        return 'settings/taxauthorities';
    }

    /**
     * @return string
     */
    public function getModelClassName()
    {
        return '\\Webleit\\ZohoBooksApi\\Models\\Settings\\TaxAuthorities';
    }
}
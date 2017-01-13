<?php

namespace Webleit\ZohoBooksApi\Modules\Settings;

use Webleit\ZohoBooksApi\Modules\Module;

/**
 * Class TaxGroups
 * @package Webleit\ZohoBooksApi\Modules
 */
class TaxGroups extends Module
{
    /**
     * @return string
     */
    public function getUrlPath()
    {
        return 'settings/taxgroups';
    }

    /**
     * @return string
     */
    public function getModelClassName()
    {
        return '\\Webleit\\ZohoBooksApi\\Models\\Settings\\TaxGroup';
    }
}
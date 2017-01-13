<?php

namespace Webleit\ZohoBooksApi\Modules\Settings;

use Webleit\ZohoBooksApi\Modules\Module;

/**
 * Class TaxExemptions
 * @package Webleit\ZohoBooksApi\Modules
 */
class TaxExemptions extends Module
{
    /**
     * @return string
     */
    public function getUrlPath()
    {
        return 'settings/taxexemptions';
    }

    /**
     * @return string
     */
    public function getModelClassName()
    {
        return '\\Webleit\\ZohoBooksApi\\Models\\Settings\\TaxExemption';
    }
}
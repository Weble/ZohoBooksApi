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

    /**
     * Return Zoho API name
     * 
     * Note that this is different from the request. The request
     * is for 'taxexemptions', but it returns an array with a key
     * of "tax_exemptions"
     */
    public function getApiName()
    {
        return "tax_exemptions";
    }
}

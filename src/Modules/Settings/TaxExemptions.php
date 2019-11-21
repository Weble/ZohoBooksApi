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
     * This overrides getResourceKey in Module, which normally
     * returns strtolower of name. However, Zoho returns "tax_exemptions"
     * instead of the expected 'taxexceptions' key.
     */
    public function getResourceKey()
    {
        return "tax_exemptions";
    }

    /**
     * Return Zoho API Key Name
     *
     * This is the unique key used to return data. With the taxexcemptions
     * module, it returns the data as an array in 'tax_exceptions' but the
     * actual key is 'tax_exception_id', without the trailing s.
     */
    public function getApiKeyName()
    {
        return "tax_exemption_id";
    }
}

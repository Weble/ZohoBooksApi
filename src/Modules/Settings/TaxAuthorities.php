<?php

namespace Webleit\ZohoBooksApi\Modules\Settings;

use Webleit\ZohoBooksApi\Models\Settings\TaxAuthority;
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
        return TaxAuthority::class;
    }

    /**
     * Return Zoho API name
     *
     * This overrides getResourceKey in Module, which normally
     * returns strtolower of name. However, Zoho returns "tax_authorities"
     * instead of the expected 'taxauthorities' key.
     */
    public function getResourceKey()
    {
        return "tax_authorities";
    }

    /**
     * Return Zoho API Key Name
     *
     * This is the unique key used to return data. With the taxauthorities
     * module, it returns the data as an array in 'tax_authorities' but the
     * actual key is 'tax_authority_id', without the trailing s.
     */
    public function getApiKeyName()
    {
        return "tax_authoritiy_id";
    }
}


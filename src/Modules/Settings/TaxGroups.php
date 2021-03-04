<?php

namespace Webleit\ZohoBooksApi\Modules\Settings;

use Weble\ZohoClient\Exception\ApiError;
use Webleit\ZohoBooksApi\Models\Settings\TaxGroup;
use Webleit\ZohoBooksApi\Modules\Module;

/**
 * Class TaxGroups
 * @package Webleit\ZohoBooksApi\Modules
 */
class TaxGroups extends Module
{
    /**
     * @param  array  $params
     * @return \Illuminate\Support\Collection|void
     * @throws ApiError
     */
    public function getList($params = [])
    {
        throw new ApiError('The getList() api for TaxGroups is not implemented in Zoho APIs');
    }

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
        return TaxGroup::class;
    }

    /**
    * Override returned key
    *
    * This overrides the key that is returned from zoho, as they
    * send back 'tax_groups' instead of 'taxgroups'
    *
    * @return string
    */
    public function getResourceKey()
    {
        return 'tax_groups';
    }
}

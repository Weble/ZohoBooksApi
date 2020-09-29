<?php

namespace Webleit\ZohoBooksApi\Modules;

use Webleit\ZohoBooksApi\Models\Organization;

/**
 * Class Organizations
 * @package Webleit\ZohoBooksApi\Modules
 */
class Organizations extends Module
{
    /**
     * @return bool|Organization
     */
    public function getDefaultOrganization()
    {
        $organizations = $this->getList();

        foreach ($organizations as $organization) {
            if ($organization->is_default_org) {
                return $organization;
            }
        }

        return false;
    }

    /**
     * @return bool|string
     */
    public function getDefaultOrganizationId()
    {
        $organization = $this->getDefaultOrganization();

        return $organization ? $organization->getId() : false;
    }

    /**
     * @param $data
     * @return array
     */
    public function addAddress($data)
    {
        $data = $this->client->post($this->getUrl() . '/address', $data);
        return $data['organization_address'];
    }

    /**
     * @param $id
     * @param $data
     * @return array
     */
    public function updateAddress($id, $data)
    {
        $data = $this->client->put($this->getUrl() . '/address/' . $id, null, $data);
        return $data['organization_address'];
    }
}

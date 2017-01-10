<?php

namespace Webleit\ZohoBooksApi\Modules;

/**
 * Class Organizations
 * @package Webleit\ZohoBooksApi\Modules
 */
class Organizations extends Module
{
    /**
     * @return bool|array
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

        return $organization ? $organization->getId(): false;
    }
}
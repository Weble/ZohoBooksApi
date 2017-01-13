<?php

namespace Webleit\ZohoBooksApi\Modules;
use Webleit\ZohoBooksApi\Models\User;

/**
 * Class Users
 * @package Webleit\ZohoBooksApi\Modules
 */
class Users extends Module
{
    /**
     * @return User
     */
    public function getCurrentUser()
    {
        $data = $this->client->get($this->getUrl() . '/me');
        return new User($data['user'], $this);
    }

    /**
     * @param $id
     * @return bool
     */
    public function invite($id)
    {
        return $this->doAction($id, 'invite');
    }

    /**
     * @param $id
     * @return bool
     */
    public function markAsActive($id)
    {
        return $this->doAction($id, 'active');
    }

    /**
     * @param $id
     * @return bool
     */
    public function markAsInactive($id)
    {
        return $this->doAction($id, 'inactive');
    }
}
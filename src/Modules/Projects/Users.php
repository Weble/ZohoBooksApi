<?php

namespace Webleit\ZohoBooksApi\Modules\Projects;

use Webleit\ZohoBooksApi\Models\Project;
use Webleit\ZohoBooksApi\Modules\SubModule;

/**
 * Class Tasks
 * @package Webleit\ZohoBooksApi\Modules\Projects
 *
 * @method Project\User get($id)
 */
class Users extends SubModule
{
    /**
     * @param $data
     * @return mixed
     */
    public function assign($data)
    {
        $data = $this->client->post($this->getUrl(), $data);
        return $data['users'];
    }

    /**
     * @param $data
     * @return mixed
     */
    public function invite($data)
    {
        $data = $this->client->post($this->getUrl() . '/invite', $data);
        return $data['user'];
    }
}

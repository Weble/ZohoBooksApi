<?php

namespace Webleit\ZohoBooksApi\Modules;

use Webleit\ZohoBooksApi\Models\Project;
use Webleit\ZohoBooksApi\Modules\Mixins\Commentable;
use Webleit\ZohoBooksApi\Modules\Projects\Tasks;
use Webleit\ZohoBooksApi\Modules\Projects\TimeEntries;
use Webleit\ZohoBooksApi\Modules\Projects\Users;

/**
 * Class Projects
 * @package Webleit\ZohoBooksApi\Modules
 */
class Projects extends Module
{
    use Commentable;

    /**
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function getTasks($id)
    {
        $tasks = new Tasks($this->client, $this->get($id));
        $className = $tasks->getModelClassName();

        return $this->getPropertyList('tasks', $id, $className, 'tasks', $tasks);
    }

    /**
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function getUsers($id)
    {
        $tasks = new Users($this->client, $this->get($id));
        $className = $tasks->getModelClassName();

        return $this->getPropertyList('users', $id, $className, 'users', $tasks);
    }

    /**
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function getTimeEntries($id)
    {
        $tasks = new TimeEntries($this->client);
        $className = $tasks->getModelClassName();

        return $this->getPropertyList('timeentries', $id, $className, 'time_entries', $tasks);
    }

    /**
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function getInvoices($id)
    {
        return $this->getPropertyList('invoices', $id);
    }

    /**
     * @param $id
     */
    public function activate($id)
    {
        $this->doAction($id, 'active');
    }

    /**
     * @param $id
     */
    public function inactivate($id)
    {
        $this->doAction($id, 'inactive');
    }

    /**
     * @param $id
     */
    public function deactivate($id)
    {
        $this->inactivate($id);
    }

    /**
     * @param $id
     * @param $name
     * @param string $description
     * @return Project
     */
    public function cloneAs($id, $name, $description = '')
    {
        $data = [
            'project_name' => $name,
            'description' => $description
        ];

        $data = $this->client->post($this->getUrl() . '/' .  $id . '/clone', $data);
        return new Project($this, $data);
    }
}

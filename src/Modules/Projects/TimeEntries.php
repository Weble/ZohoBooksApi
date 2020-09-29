<?php

namespace Webleit\ZohoBooksApi\Modules\Projects;

use Webleit\ZohoBooksApi\Models\Project;
use Webleit\ZohoBooksApi\Modules\Module;

/**
 * Class TimeEntries
 * @package Webleit\ZohoBooksApi\Modules\Projects
 *
 * @method Project\TimeEntry get($id)
 */
class TimeEntries extends Module
{
    /**
     * @return string
     */
    public function getUrlPath()
    {
        return 'projects/timeentries';
    }

    /**
     * @return string
     */
    public function getModelClassName()
    {
        return '\\Webleit\\ZohoBooksApi\\Models\\Project\\TimeEntry';
    }

    /**
     * @param $data
     * @return mixed
     */
    public function log($data)
    {
        $data = $this->client->post($this->getUrl(), $data);
        return new Project\TimeEntry($data['time_entry'], $this);
    }

    /**
     * @param $ids
     * @return bool
     */
    public function deleteList($ids)
    {
        $this->client->delete($this->getUrl(), null, ['time_entry_ids' => $ids]);
        return true;
    }

    /**
     * @return Project\TimeEntry
     */
    public function getTimer()
    {
        $data = $this->client->get($this->getUrl() . '/runningtimer/me');
        return new Project\TimeEntry($data['time_entry'], $this);
    }

    /**
     * @param $id
     * @return Project\TimeEntry
     */
    public function startTime($id)
    {
        $data = $this->client->post($this->getUrl() . '/' . $id . '/timer/start');
        return new Project\TimeEntry($data['time_entry'], $this);
    }

    /**
     * @param $id
     * @return Project\TimeEntry
     */
    public function stopTimer($id)
    {
        $data = $this->client->post($this->getUrl() . '/' . $id . '/timer/stop');
        return new Project\TimeEntry($data['time_entry'], $this);
    }

    /**
     * @param $id
     * @param $data
     * @return Project\TimeEntry
     */
    public function update($id, $data, $params = [])
    {
        $data = $this->client->put($this->getUrl(), $id, $data);
        return new Project\TimeEntry($data['time_entry'], $this);
    }
}

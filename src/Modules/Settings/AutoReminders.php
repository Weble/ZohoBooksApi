<?php

namespace Webleit\ZohoBooksApi\Modules\Settings;

use Webleit\ZohoBooksApi\Modules\Module;

/**
 * Class AutoReminders
 * @package Webleit\ZohoBooksApi\Modules
 */
class AutoReminders extends Module
{
    /**
     * @return string
     */
    public function getUrlPath()
    {
        return 'settings/autoreminders';
    }

    /**
     * @return string
     */
    public function getModelClassName()
    {
        return '\\Webleit\\ZohoBooksApi\\Models\\Settings\\AutoReminder';
    }

    /**
     * @param $id
     * @return bool
     */
    public function enable($id)
    {
        return $this->doAction($id, 'enable');
    }

    /**
     * @param $id
     * @return bool
     */
    public function disable($id)
    {
        return $this->doAction($id, 'disable');
    }
}
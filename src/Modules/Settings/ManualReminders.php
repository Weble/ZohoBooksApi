<?php

namespace Webleit\ZohoBooksApi\Modules\Settings;

use Webleit\ZohoBooksApi\Modules\Module;

/**
 * Class ManualReminders
 * @package Webleit\ZohoBooksApi\Modules
 */
class ManualReminders extends Module
{
    /**
     * @return string
     */
    public function getUrlPath()
    {
        return 'settings/manualreminders';
    }

    /**
     * @return string
     */
    public function getModelClassName()
    {
        return '\\Webleit\\ZohoBooksApi\\Models\\Settings\\ManualReminder';
    }
}
<?php

namespace Webleit\ZohoBooksApi\Models\Project;

use Webleit\ZohoBooksApi\Models\Model;

/**
 * Class TimeEntry
 * @package Webleit\ZohoBooksApi\Models\Project
 */
class TimeEntry extends Model
{
    /**
     * @return string
     */
    public function getKeyName()
    {
        return 'time_entry_id';
    }
}
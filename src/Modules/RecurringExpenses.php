<?php

namespace Webleit\ZohoBooksApi\Modules;

use Illuminate\Support\Collection;

/**
 * Class RecurringExpenses
 * @package Webleit\ZohoBooksApi\Modules
 */
class RecurringExpenses extends Documents
{
    /**
     * @param $id string
     * @return boolean
     */
    public function stop($id)
    {
        return $this->markAs($id, 'stop');
    }

    /**
     * @param $id string
     * @return boolean
     */
    public function resume($id)
    {
        return $this->markAs($id, 'resume');
    }

    /**
     * @param $id
     * @return Collection
     */
    public function getChildExpenses($id)
    {
        return $this->getPropertyList('expenses', $id);
    }

    /**
     * Override returned key
     *
     * This overrides the key that is returned from zoho, as they
     * send back 'recurring_expenses' instead of 'recurringexpenses'
     *
     * @return string
     */
    public function getResourceKey()
    {
        return 'recurring_expenses';
    }
}

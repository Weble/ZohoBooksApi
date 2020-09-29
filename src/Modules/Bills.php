<?php

namespace Webleit\ZohoBooksApi\Modules;

use Webleit\ZohoBooksApi\Modules\Mixins\Commentable;
use Illuminate\Support\Collection;
use Webleit\ZohoBooksApi\Modules\Mixins\Payable;

/**
 * Class Bills
 * @package Webleit\ZohoBooksApi\Modules
 */
class Bills extends Module
{
    use Commentable, Payable;

    /**
     * @param $id string
     * @return boolean
     */
    public function markAsOpen($id)
    {
        return $this->markAs($id, 'open');
    }

    /**
     * @param $id string
     * @return boolean
     */
    public function markAsVoid($id)
    {
        return $this->markAs($id, 'void');
    }

    /**
     * @param $id
     * @param array $data
     * @return boolean
     */
    public function updateBillingAddress($id, $data = [])
    {
        $this->client->put($this->getUrl() . '/' . $id . '/address/billing', null, $data);
        // If we arrive here without exceptions, everything went well
        return true;
    }
}

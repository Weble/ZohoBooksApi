<?php

namespace Webleit\ZohoBooksApi\Modules\Settings;

use Doctrine\Common\Inflector\Inflector;
use Illuminate\Support\Collection;
use Webleit\ZohoBooksApi\Modules\Module;

/**
 * Class OpeningBalances
 * @package Webleit\ZohoBooksApi\Modules
 */
class OpeningBalances extends Module
{
    /**
     * @return string
     */
    public function getUrlPath()
    {
        return 'settings/openingbalances';
    }

    /**
     * @return string
     */
    public function getModelClassName()
    {
        return '\\Webleit\\ZohoBooksApi\\Models\\Settings\\OpeningBalance';
    }

    /**
     * @return string
     */
    protected function getResourceKey()
    {
        return 'opening_balance';
    }

    /**
     * @return Collection
     */
    public function getList($params = [])
    {
        return new Collection([$this->get()]);
    }

    /**
     * @param null $id
     * @return \Webleit\ZohoBooksApi\Models\Model
     */
    public function get($id = null)
    {
        return parent::get($id);
    }
}
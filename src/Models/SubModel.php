<?php

namespace Webleit\ZohoBooksApi\Models;

class SubModel extends Model
{
    /**
     * @var Model
     */
    protected $parent;

    public function setParent(Model $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return Model
     */
    public function getParent()
    {
        return $this->parent;
    }
}
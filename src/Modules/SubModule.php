<?php

namespace Webleit\ZohoBooksApi\Modules;

use Webleit\ZohoBooksApi\Models\Model;
use Doctrine\Common\Inflector\Inflector;

/**
 * Class Module
 * @package Webleit\ZohoBooksApi\Modules
 */
abstract class SubModule extends Module
{
    /**
     * @return string
     */
    public function getModelClassName()
    {
        $className = (new \ReflectionClass($this))->getShortName();
        return parent::getModelClassName() . '\\' . $this->inflector->singularize($this->inflector->camelize($className));
    }

    /**
     * @return string
     */
    public function getUrlPath()
    {
        $path = $this->getParent()->getModule()->getUrlPath() . '/' . $this->getParent()->getId() . '/';
        $path .= parent::getUrlPath();

        return $path;
    }

    /**
     * @var Model
     */
    protected $parent;

    /**
     * @param Model $parent
     */
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

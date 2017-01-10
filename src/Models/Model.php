<?php
namespace Webleit\ZohoBooksApi\Models;

use Doctrine\Common\Inflector\Inflector;
use Webleit\ZohoBooksApi\Modules\Module;

abstract class Model
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var Module
     */
    protected $module;

    /**
     * Model constructor.
     * @param array $data
     * @param  Module   $module
     */
    public function __construct($data = [], Module $module)
    {
        $this->data = $data;
        $this->module = $module;
    }

    /**
     * @param $name
     * @return mixed
     */
    function __get($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
    }

    /**
     * @param $name
     * @param $value
     */
    function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    function __call($name, $arguments)
    {
        // add "id" as a parameter
        array_unshift($arguments, $this->getId());

        if (method_exists($this->module, $name)) {
            return call_user_func_array([$this->module, $name], $arguments);
        }
    }

    /**
     * is a new object?
     * @return bool
     */
    public function isNew()
    {
        return !$this->getId();
    }

    /**
     * Get the id of the object
     * @return bool|string
     */
    public function getId()
    {
        $key = $this->getKeyName();
        return $this->$key ? $this->$key : false;
    }

    /**
     * Get the name of the primary key
     */
    public function getKeyName()
    {
        return strtolower($this->getName() . '_id');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return Inflector::singularize(strtolower((new \ReflectionClass($this))->getShortName()));
    }
}
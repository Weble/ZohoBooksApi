<?php

namespace Webleit\ZohoBooksApi\Mixins;

use Doctrine\Common\Inflector\Inflector;

trait ProvidesModules
{
    /**
     * Proxy any module call to the right api call
     * @param $name
     * @return mixed
     */
    public function createModule($name)
    {
        $class = $this->modulesNamespace . $this->getModuleShortClassName($name);

        return new $class($this->client, $this);
    }

    /**
     * Get the list of available modules
     * @return array
     */
    public function getAvailableModules()
    {
        return array_keys($this->availableModules);
    }

    /**
     * Get the class name of a module
     * @return string
     */
    public function getModuleShortClassName($moduleName, $model = false)
    {
        $module = strtolower($this->getModuleName($moduleName));

        if (isset($this->availableModules[$module])) {
            $class =  $this->availableModules[$module];
        } else {
            $class = ucfirst(strtolower($moduleName));
        }

        return str_replace('|s', $model ? '' : 's', $class);
    }

    /**
     * Get the module name by joining any camelcase module name and trying plural vs singular
     * @param $moduleName
     * @return string
     */
    protected function getModuleName($moduleName)
    {
        // Try also singular
        $module = $moduleName;
        if (isset($this->availableModules[$moduleName])) {
            $module = $moduleName;
        } else {
            $moduleName = Inflector::pluralize($moduleName);

            if (isset($this->availableModules[$moduleName])) {
                $module = $moduleName;
            }
        }

        return $module;
    }
}
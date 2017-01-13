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
        $module = $this->getModuleName($name);

        if (in_array($module, $this->availableModules)) {
            $class =  $this->modulesNamespace . ucfirst((Inflector::camelize($module)));
            return new $class($this->client);
        }
    }

    /**
     * Get the list of available modules
     * @return array
     */
    public function getAvailableModules()
    {
        return $this->availableModules;
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
        if (in_array($moduleName, $this->availableModules)) {
            $module = $moduleName;
        } else {
            $moduleName = Inflector::pluralize($moduleName);

            if (in_array($moduleName, $this->availableModules)) {
                $module = $moduleName;
            }
        }

        return $module;
    }
}
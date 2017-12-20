<?php

namespace Webleit\ZohoBooksApi\Modules;

use Doctrine\Common\Inflector\Inflector;
use Illuminate\Support\Collection;
use Webleit\ZohoBooksApi\Client;
use Webleit\ZohoBooksApi\Models\Model;

/**
 * Class Module
 * @package Webleit\ZohoBooksApi\Modules
 */
abstract class Module implements \Webleit\ZohoBooksApi\Contracts\Module
{
    /**
     * Response types
     */
    const RESPONSE_OPTION_PAGINATION_ONLY = 2;

    /**
     * Base Url of the Zoho Books Api
     * @var string
     */
    const ENDPOINT = 'https://books.zoho.com/api/v3/';

    /**
     * @var Client
     */
    protected $client;

    /**
     * Api constructor.
     */
    function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get the list of the resources requested
     * @param array $params
     * @return Collection
     */
    public function getList($params = [])
    {
        $list = $this->client->getList($this->getUrl(), null, $params);

        $collection = new Collection($list[$this->getResourceKey()]);
        $collection = $collection->mapWithKeys(function($item) {
            $item = $this->make($item);
            return [$item->getId() => $item];
        });

        return $collection;
    }

    /**
     * Get a single record for this module
     * @param string $id
     * @return Model
     */
    public function get($id)
    {
        $item = $this->client->get($this->getUrl(), $id);
        $data = $item[Inflector::singularize($this->getResourceKey())];

        return $this->make($data);
    }

    /**
     * Get the total records for a module
     * @return int
     */
    public function getTotal()
    {
        $list = $this->client->getList($this->getUrl(), null, ['response_option' => self::RESPONSE_OPTION_PAGINATION_ONLY]);
        return $list['page_context']['total'];
    }

    /**
     * Creates a new record for this module
     * @param array $data
     * @param array $params
     * @return Model
     */
    public function create($data, $params = [])
    {
        $data = $this->client->post($this->getUrl(), null, $data, $params);
        $data = $data[Inflector::singularize($this->getResourceKey())];

        return $this->make($data);
    }

    /**
     * Update a record for this module
     * @param string $id
     * @param array $data
     * @param array $params
     * @return Model
     */
    public function update($id, $data, $params = [])
    {
        $data = $this->client->put($this->getUrl(), $id, null, $data, $params);
        $data = $data[Inflector::singularize($this->getResourceKey())];

        return $this->make($data);
    }

    /**
     * Deletes a record for this module
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $this->client->delete($this->getUrl(), $id);

        // all is ok if we've reached this point
        return true;
    }

    /**
     * Get the url path for the api of this module (ie: /organizations)
     * @return string
     */
    public function getUrlPath()
    {
        // Module specific url path?
        if (isset($this->urlPath) && $this->urlPath) {
            return $this->urlPath;
        }

        // Class name
        return $this->getName();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return Inflector::pluralize(strtolower((new \ReflectionClass($this))->getShortName()));
    }

    /**
     * Get the full api url to this module
     * @return string
     */
    public function getUrl()
    {
        return self::ENDPOINT . $this->getUrlPath();
    }

    /**
     * @return string
     */
    protected function getResourceKey()
    {
        return strtolower($this->getName());
    }

    /**
     * @param  array $data
     * @return Model
     */
    protected function make($data = [])
    {
        $class = $this->getModelClassName();

        return new $class($data, $this);
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param $id
     * @param $status
     * @param string $key
     * @return bool
     */
    public function markAs($id, $status, $key = 'status')
    {
        $this->client->post($this->getUrl() . '/' . $id . '/' . $key . '/' . $status);
        // If we arrive here without exceptions, everything went well
        return true;
    }

    /**
     * @param $id
     * @param $action
     * @param array $data
     * @param array $params
     * @return bool
     */
    public function doAction($id, $action, $data = [], $params = [])
    {
        $this->client->post($this->getUrl() . '/' . $id . '/' . $action, null, $data, $params);

        // If we arrive here without exceptions, everything went well
        return true;
    }

    /**
     * @param $property
     * @param null $id
     * @param null $class
     * @param null $subProperty
     * @param null $module
     * @return Collection
     */
    protected function getPropertyList($property, $id = null, $class = null, $subProperty = null, $module = null)
    {
        if (!$class) {
            $class = $this->getModelClassName() . '\\' . ucfirst(strtolower(Inflector::singularize($property)));
        }

        if (!$module) {
            $module = $this;
        }

        if (!$subProperty) {
            $subProperty = $property;
        }

        $url = $this->getUrl();
        if ($id !== null) {
            $url .= '/' . $id;
        }
        $url .= '/' . $property;

        $list = $this->client->getList($url);

        $collection = new Collection($list[$subProperty]);
        $collection = $collection->mapWithKeys(function ($item) use ($class, $module) {
            /** @var Model $item */
            $item = new $class($item, $module);
            return [$item->getId() => $item];
        });

        return $collection;
    }

    /**
     * @return string
     */
    public function getModelClassName()
    {
        $className = (new \ReflectionClass($this))->getShortName();
        $class = '\\Webleit\\ZohoBooksApi\\Models\\' . ucfirst(strtolower(Inflector::singularize($className)));

        return $class;
    }
}

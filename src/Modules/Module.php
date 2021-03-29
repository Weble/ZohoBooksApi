<?php

namespace Webleit\ZohoBooksApi\Modules;

use Doctrine\Inflector\InflectorFactory;
use Illuminate\Support\Collection;
use Webleit\ZohoBooksApi\Client;
use Webleit\ZohoBooksApi\Models\Model;
use Webleit\ZohoBooksApi\Models\PageContext;
use Webleit\ZohoBooksApi\Models\RecordCollection;
use Webleit\ZohoBooksApi\Request\Pagination;

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
     * @var Client
     */
    protected $client;

    /**
     * @var \Doctrine\Inflector\Inflector
     */
    protected $inflector;

    /**
     * Api constructor.
     */
    function __construct(Client $client)
    {
        $this->client = $client;
        $this->inflector = InflectorFactory::create()->build();
    }

    /**
     * Get the list of the resources requested
     * @param array $params
     * @return RecordCollection
     */
    public function getList($params = [])
    {
        $list = $this->client->getList($this->getUrl(), $params);

        $collection = new RecordCollection($list[$this->getResourceKey()]);
        $collection = $collection->mapWithKeys(function ($item) {
            $item = $this->make($item);
            return [$item->getId() => $item];
        });

        $collection->withPagination(new Pagination($list['page_context'] ?? []));

        return $collection;
    }

    /**
     * Get a single record for this module
     * @param string $id
     * @return Model|string
     */
    public function get($id, array $params = [])
    {
        $item = $this->client->get($this->getUrl(), $id, $params);

        if (!is_array($item)) {
            return $item;
        }

        $data = $item[$this->inflector->singularize($this->getResourceItemKey())];

        return $this->make($data);
    }

    /**
     * Get the total records for a module
     * @return int
     */
    public function getTotal()
    {
        $list = $this->client->getList($this->getUrl(), ['response_option' => self::RESPONSE_OPTION_PAGINATION_ONLY]);
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
        $data = $this->client->post($this->getUrl(), $data, $params);
        $data = $data[$this->inflector->singularize($this->getResourceItemKey())];

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
        $data = $this->client->put($this->getUrl(), $id, $data, $params);
        $data = $data[$this->inflector->singularize($this->getResourceItemKey())];

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
        return $this->inflector->pluralize(strtolower((new \ReflectionClass($this))->getShortName()));
    }

    /**
     * Get the full api url to this module
     * @return string
     */
    public function getUrl()
    {
        return $this->getUrlPath();
    }

    /**
     * This is used to determine the key of the returned data
     *
     * Note that some modules (eg, Settings\TaxExemptions) override
     * this value, because zoho does not return the data with the
     * expected key. If you are looking at this code, you may also
     * need to override the api key name, too.
     *
     * @return string
     */
    public function getResourceKey()
    {
        return strtolower($this->getName());
    }

    /**
     * This is used to determine the key of the returned data in get() calls
     *
     * Note that some modules (eg, Settings\TaxExemptions) override
     * this value, because zoho does not return the data with the
     * expected key. If you are looking at this code, you may also
     * need to override the api key name, too.
     *
     * @return string
     */
    public function getResourceItemKey()
    {
        return $this->getResourceKey();
    }

    /**
     * @param  array $data
     * @return Model
     */
    public function make($data = [])
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
     * Mark something with a status
     *
     * Note that as of 2019-11-27, Zoho Books API errors if you don't
     * provide a JSONString param. It can't be empty.
     *
     * See https://github.com/Weble/ZohoBooksApi/issues/33
     *
     * @param $id
     * @param $status
     * @param string $key
     * @return bool
     */
    public function markAs($id, $status, $key = 'status')
    {
        $this->client->post(
            $this->getUrl() . '/' . $id . '/' . $key . '/' . $status,
            ["random" => "data"]
        );
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
        $this->client->post($this->getUrl() . '/' . $id . '/' . $action, $data, $params);

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
            $class = $this->getModelClassName() . '\\' . ucfirst(strtolower($this->inflector->singularize($property)));
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
        $class = '\\Webleit\\ZohoBooksApi\\Models\\' . ucfirst($this->inflector->singularize($className));

        return $class;
    }
}

<?php

namespace Webleit\ZohoBooksApi\Modules;

use Doctrine\Inflector\InflectorFactory;
use Illuminate\Support\Collection;
use Webleit\ZohoBooksApi\Client;

/**
 * Class PreferenceModule
 * @package Webleit\ZohoBooksApi\Modules
 */
abstract class PreferenceModule implements \Webleit\ZohoBooksApi\Contracts\Module
{
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
     * @return Collection
     */
    public function getList($params = [])
    {
        $list = $this->client->getList($this->getUrl(), $params);

        $collection = new Collection($list[$this->getAnswerKeyName()]);
        return $collection;
    }

    /**
     * Update settings for this module
     * @param array $data
     * @return Collection
     */
    public function update($data)
    {
        $data = $this->client->put($this->getUrl(), null, $data);
        $collection = new Collection($data[$this->getAnswerKeyName()]);

        return $collection;
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
        return 'settings/' . $this->getName();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->inflector->pluralize(strtolower((new \ReflectionClass($this))->getShortName()));
    }

    /**
     * @return string
     */
    protected function getAnswerKeyName()
    {
        return $this->getName();
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
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }
}

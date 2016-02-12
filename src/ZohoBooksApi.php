<?php

namespace Weble\ZohoBooksApi;

use GuzzleHttp\Client;
use Doctrine\Common\Inflector\Inflector;

class ZohoBooksApi
{
    /**
     * Zoho Books Api Auth Token
     * @var string
     */
    protected $authToken = '';

    /**
     * Zoho Books Organization Id
     * @var string
     */
    protected $organizationId = '';

    /**
     * Base Url of the Zoho Books Api
     * @var string
     */
    protected $apiUrl = 'https://books.zoho.com/api/v3/';

    /**
     * Guzzle client to deal with the request
     * @var Client
     */
    protected $client;

    /**
     * Stored locally the total number per resource type
     * @var array
     */
    protected $totals = [];

    /**
     * List of available Zoho Books Api endpoints (see https://www.zoho.com/books/api/v3)
     * @var array
     */
    protected $availableModules = [
        'contacts',
        'estimates',
        'salesorders',
        'invoices',
        'recurringinvoices',
        'creditnotes',
        'customerpayments',
        'expenses',
        'recurringexpenses',
        'purchaseorders',
        'bills',
        'vendorcredits',
        'vendorpayments',
        'bankaccounts',
        'banktransactions',
        'bankrules',
        'chartofaccounts',
        'journals',
        'basecurrencyadjustment',
        'projects',
        'settings'
    ];

    /**
     * ZohoBooksApi constructor.
     * @param $authToken    Zoho Books Api Token (See https://www.zoho.com/books/api/v3/)
     * @param string $organizationId The organization id you want to deal with (See https://www.zoho.com/books/api/v3/)
     */
    public function __construct($authToken, $organizationId = '')
    {
        $this->authToken = $authToken;
        $this->organizationId = $organizationId;

        $this->client = new Client([
           'base_uri' => $this->apiUrl
        ]);
    }

    /**
     * Transform any get<Module> or save<Module> in the right request
     * @param $name
     * @param $arguments
     * @throws \Exception
     *
     * @return array
     */
    public function __call($name, $arguments)
    {
        // Use camelCase syntax to convert (getInvoices) => get invoices
        $matches = [];
        preg_match_all('/((?:^|[A-Z])[a-z]+)/', $name, $matches);

        if (count($matches[0]) > 1) {

            // This is the method name
            $method = false;
            switch ($matches[0][0]) {
                case 'get':
                case 'list':
                case 'all':
                    $method = 'GET';
                    break;
                case 'save':
                case 'update':
                case 'put':
                case 'post':
                    $method = 'POST';
                    break;
                case 'delete':
                case 'remove':
                    $method = 'DELETE';
                    break;
            }

            // Special case: getXXXTotal
            $last = $matches[0][count($matches[0]) - 1];
            $isTotalCall = false;

            if (strtoupper($last) == 'TOTAL') {
                $isTotalCall = true;
                array_pop($matches[0]);
            }

            // Convert any getSalesOrders to GET => salesorders by joining any camelcase module name
            $moduleName = strtolower(implode("", array_splice($matches[0], 1)));

            // Try also singular
            $module = false;
            if (in_array($moduleName, $this->availableModules)) {
                $module = $moduleName;
            } else {
                $moduleName = Inflector::pluralize($moduleName);

                if (in_array($moduleName, $this->availableModules)) {
                    $module = $moduleName;
                }
            }

            // Special case: getXXXTotal
            if ($isTotalCall && $method == 'GET') {
                return $this->getTotal($module);
            }

            // pass along any argument as data
            $data = null;
            if ($arguments && count($arguments)) {
                $data = $arguments[0];
            }

            if ($method && $module) {
                return $this->performRequest($module, $method, $data);
            }
        }
    }

    /**
     * Generic method to call the api
     *
     * @param $module   string  One of the available modules
     * @param $method   string  HTTP method to use
     * @param $data     mixed The data to pass on
     *
     * @throws \Exception
     *
     * @return  array
     */
    protected function performRequest($module, $method, $data = [])
    {
        // Check if the module is available first
        if (!in_array($module, $this->availableModules)) {
            throw new \Exception('Not a valid Module');
        }

        $id = false;

        if ($data) {
            // This could be a simple int / string => suppose it's the Id
            if (!is_object($data) && !is_array($data)) {
                $id = $data;
                $data = [];
            }
        }

        $config = array_intersect_key($data, array('per_page' => 200, 'page' => 1));

        $requestData = [
            'authtoken'         => $this->authToken,
            'organization_id'   => $this->organizationId,
            'JSONString' => $data
        ];

        $requestData = array_merge($requestData, $config);

        // Build the url using the id if present
        $url = strtolower($module);
        if ($id) {
            $url .= '/' . $id;
        }

        $response = $this->client->request(strtoupper($method), $url, [
            'query' => $requestData
        ]);

        // Check if the response is correct
        $responseData = $this->getResponseData($response);

        return $this->getResponseObject($responseData, $module);
    }

    /**
     * Extract just the response object (either the item or the list requested)
     * from the response
     *
     * @param $response
     * @param $responseDataObject
     * @return array
     * @throws \Exception
     */
    protected function getResponseObject($response, $responseDataObject)
    {
        if (!isset($response[$responseDataObject])) {
            $responseDataObject = Inflector::singularize($responseDataObject);

            if (!isset($response[$responseDataObject])) {
                throw new \Exception('Cannot find the response data');
            }
        }

        return $response[$responseDataObject];
    }

    /**
     * Check if the response is ok and get the response data
     * @param $response
     * @throws \Exception
     * @return array
     */
    protected function getResponseData($response)
    {
        // Not an ok response, exit
        if ($response->getStatusCode() < 200 || $response->getStatusCode() > 299) {
            throw new \Exception($response->getReasonPhrase());
        }

        // Get the data as json
        $responseData = json_decode((string) $response->getBody(), true);

        if (!$responseData) {
            throw new \Exception('Not a valid Json');
        }

        if (!isset($responseData['code'])) {
            throw new \Exception('Not a valid Response');
        }

        if ($responseData['code'] != 0) {
            throw new \Exception($responseData['message']);
        }

        return $responseData;
    }

    public function getTotal($module)
    {
        // Check if the module is available first
        if (!in_array($module, $this->availableModules)) {
            throw new \Exception('Not a valid Module');
        }

        $requestData = [
            'authtoken'         => $this->authToken,
            'organization_id'   => $this->organizationId,
            'response_option'   => 2,
            'per_page' => '1'
        ];

        $response = $this->client->request('GET', 'invoices', [
            'query' => $requestData
        ]);

        $data = $this->getResponseData($response);

        if (!isset($data['page_context'])) {
            throw new \Exception('Cannot retrieve total');
        }

        return $data['page_context']['total'];
    }


}
<?php

namespace Webleit\ZohoBooksApi;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Weble\ZohoClient\OAuthClient;
use Webleit\ZohoBooksApi\Exceptions\AuthException;
use Webleit\ZohoBooksApi\Exceptions\ErrorResponseException;

/**
 * Class Client
 * @see https://github.com/opsway/zohobooks-api
 * @package Webleit\ZohoBooksApi
 */
class Client
{

    const ENDPOINT_CN = 'https://books.zoho.com.cn/api/v3/';
    const ENDPOINT_EU = 'https://books.zoho.eu/api/v3/';
    const ENDPOINT_IN = 'https://books.zoho.in/api/v3/';
    const ENDPOINT_US = 'https://books.zoho.com/api/v3/';

    /**
     * @var string
     */
    protected $httpClient;

    /**
     * @var OAuthClient
     */
    protected $oAuthClient;

    /**
     * default organization id
     * @var string
     */
    protected $organizationId;

    /**
     * @var string
     */
    protected $region = OAuthClient::DC_US;

    /**
     * As of Zoho BUILD_VERSION "Dec_10_2019_23492", they are returning headers
     * of 'X-Rate-Limit-Limit', 'X-Rate-Limit-Reset', and 'X-Rate-Limit-Remaining'
     * on API calls. These vars are updated with the contents of those headers, if
     * they exist.
     */

    /**
     * The rate limit of this org, as returned by the X-Rate-Limit-Limit header
     * @var int|null
     */
    protected $orgRateLimit;

    /**
     * The number of seconds remaining until the rate limit resets, as returned
     * by the 'X-Rate-Limit-Reset' header
     * @var int|null
     */
    protected $rateLimitReset;

    /**
     * The number of API calls remaining before the rate limit is reset, as returned
     * by the 'X-Rate-Limit-Remaining' header
     * @var int|null
     */
    protected $rateLimitRemaining;

    /**
     * Client constructor.
     * @param $clientId
     * @param $clientSecret
     * @param $refreshToken
     */
    public function __construct($clientId, $clientSecret, $refreshToken = null)
    {
        $this->createClient();

        $this->oAuthClient = new OAuthClient($clientId, $clientSecret, $refreshToken);
        $this->oAuthClient->setRefreshToken($refreshToken);
    }

    /**
     * @param string $region
     * @return $this
     */
    public function setRegion($region = OAuthClient::DC_US)
    {
        $this->region = $region;
        $this->createClient();

        return $this;
    }

    /**
     * @return \GuzzleHttp\Client|string
     */
    protected function createClient()
    {
        $this->httpClient = new \GuzzleHttp\Client(['base_uri' => $this->getEndPoint(), 'http_errors' => false]);
        return $this->httpClient;
    }

    /**
     * @return string
     */
    public function getEndPoint()
    {
        switch ($this->region) {
            case OAuthClient::DC_CN:
                return self::ENDPOINT_CN;
                break;
            case OAuthClient::DC_IN:
                return self::ENDPOINT_IN;
                break;
            case OAuthClient::DC_EU:
                return self::ENDPOINT_EU;
                break;
            case OAuthClient::DC_US:
            default:
                return self::ENDPOINT_US;
                break;
        }
    }

    /**
     * @return string
     */
    public function getOrganizationId()
    {
        return $this->organizationId;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param $organizationId
     * @return $this
     */
    public function setOrganizationId($organizationId)
    {
        $this->organizationId = $organizationId;
        return $this;
    }

    /**
     * @param string $url
     * @param string $organizationId
     * @param array $filters
     *
     * @return array
     */
    public function getList($url, $organizationId = null, array $filters = [])
    {
        return $this->processResult(
            $this->httpClient->get($url, $this->getOptions(['query' => array_merge($this->getParams($organizationId), $filters)]))
        );
    }

    /**
     * @param string $url
     * @param string $id
     * @param string $organizationId
     * @param array $params Additional query params
     *
     * @return array
     */
    public function get($url, $id = null, $organizationId = null, array $params = [])
    {
        if ($id !== null) {
            $url .= '/' . $id;
        }

        return $this->processResult(
            $this->httpClient->get($url, $this->getOptions(['query' => $this->getParams($organizationId) + $params]))
        );
    }

    /**
     * @param string $url
     * @param string $organizationId
     * @param array $params Additional query params
     *
     * @throws ErrorResponseException;
     *
     * @return StreamInterface
     */
    public function rawGet($url, $organizationId = null, array $params = [])
    {
        try {
            $response = $this->httpClient->get($url, $this->getOptions(['query' => $this->getParams($organizationId) + $params]));
            return $response->getBody();
        } catch (\InvalidArgumentException $e) {
            throw new ErrorResponseException('Response from Zoho is not success. Message: ' . $e);
        }
    }

    /**
     * @param string $url
     * @param string $organizationId
     * @param array $data
     * @param array $params Additional query params
     *
     * @return array
     */
    public function post($url, $organizationId = null, array $data = [], array $params = [])
    {
        return $this->processResult($this->httpClient->post(
            $url,
            $this->getOptions([
                'query' => $this->getParams($organizationId) + $params,
                'form_params' => ['JSONString' => json_encode($data)]
            ])
        ));
    }

    /**
     * @param string $url
     * @param string $id
     * @param string $organizationId
     * @param array $data
     * @param array $params Additional query params
     *
     * @return array
     */
    public function put($url, $id = null, $organizationId = null, array $data = [], array $params = [])
    {
        if ($id !== null) {
            $url .= '/' . $id;
        }

        return $this->processResult($this->httpClient->put(
            $url,
            $this->getOptions([
                'query' => $this->getParams($organizationId) + $params,
                'form_params' => ['JSONString' => json_encode($data)]
            ])
        ));
    }

    /**
     * @param string $url
     * @param string $id
     * @param string $organizationId
     *
     * @return array
     */
    public function delete($url, $id = null, $organizationId = null)
    {
        if ($id !== null) {
            $url .= '/' . $id;
        }

        return $this->processResult(
            $this->httpClient->delete($url, $this->getOptions(['query' => $this->getParams($organizationId)]))
        );
    }

    /**
     * @param string $organizationId
     * @param array $data
     *
     * @return array
     */
    protected function getParams($organizationId = null, array $data = [])
    {
        if (!$organizationId) {
            $organizationId = $this->organizationId;
        }

        $params = [];

        if ($organizationId) {
            $params['organization_id'] = $organizationId;
        }

        if ($data) {
            $params['JSONString'] = json_encode($data);
        }

        return $params;
    }

    /**
     * @param array $params
     * @return array
     */
    protected function getOptions($params = [])
    {
        return array_merge([
            'headers' => [
                'Authorization' => 'Zoho-oauthtoken ' . $this->oAuthClient->getAccessToken()
            ]
        ], $params);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->oAuthClient, $name], $arguments);
    }

    /**
     * @param ResponseInterface $response
     *
     * @throws ErrorResponseException
     *
     * @return array|string
     */
    protected function processResult(ResponseInterface $response)
    {
        // Update the API Limit variables if they have been returned.
        $this->orgRateLimit = (int) $response->getHeaderLine('X-Rate-Limit-Limit', 0);
        $this->rateLimitRemaining = (int) $response->getHeaderline('X-Rate-Limit-Remaining', 0);
        $this->rateLimitReset = (int) $response->getHeaderLine('X-Rate-Limit-Reset', 0);

        try {
            $result = json_decode($response->getBody(), true);
        } catch (\InvalidArgumentException $e) {

            // All ok, probably not json, like PDF?
            if ($response->getStatusCode() >= 200 && $response->getStatusCode() <= 299) {
                return (string) $response->getBody();
            }

            $result = [
                'message' => 'Internal API error: ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase(),
            ];
        }

        if (!$result) {
            // All ok, probably not json, like PDF?
            if ($response->getStatusCode() >= 200 && $response->getStatusCode() <= 299) {
                return (string) $response->getBody();
            }

            $result = [
                'message' => 'Internal API error: ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase(),
            ];
        }

        if (isset($result['code']) && 0 == $result['code']) {
            return $result;
        }

        throw new ErrorResponseException('Response from Zoho is not success. Message: ' . $result['message']);
    }

    /**
     * Return the rate limits for this org.
     *
     * These values are taken from the headers provided by Zoho 
     * as of BUILD_VERSION "Dec_10_2019_23492". If these values
     * are not provided, or are invalid, they will be (int) 0
     */

    /**
     * @return int|null
     */
    public function getOrgRateLimit()
    {
        return $this->orgRateLimit;
    }

    /**
     * @return int|null
     */
    public function getRateLimitReset()
    {
        return $this->rateLimitReset;
    }

    /**
     * @return int|null
     */
    public function getRateLimitRemaining()
    {
        return $this->rateLimitRemaining;
    }
}

<?php

namespace Webleit\ZohoBooksApi;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Weble\ZohoClient\Enums\Region;
use Weble\ZohoClient\OAuthClient;
use Webleit\ZohoBooksApi\Exceptions\ErrorResponseException;

/**
 * Class Client
 * @see https://github.com/opsway/zohobooks-api
 * @package Webleit\ZohoBooksApi
 */
class Client
{
    /**
     * Region to Url Mapping
     * @var string[]
     */
    protected $regionDomain = [
        Region::US => 'https://books.zoho.com/api/v3/',
        Region::AU => 'https://books.zoho.com.au/api/v3/',
        Region::EU => 'https://books.zoho.eu/api/v3/',
        Region::IN => 'https://books.zoho.in/api/v3/',
        Region::CN => 'https://books.zoho.com.cn/api/v3/',
    ];
    
    /**
     * @var bool
     */
    protected $retriedRefresh = false;

    /**
     * Whilst this is technically a ClientInterface, we'll just mark it as a standard
     * client, with all the methods in place.
     *
     * @var \GuzzleHttp\Client
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

    public function __construct(OAuthClient $oAuthClient, ClientInterface $client = null)
    {
        if (!$client) {
            $client = new \GuzzleHttp\Client();
        }

        $this->httpClient = $client;
        $this->oAuthClient = $oAuthClient;
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->oAuthClient, $name)) {
            return call_user_func_array([
                $this->oAuthClient,
                $name
            ], $arguments);
        }
    }

    public function setRegion(string $region): self
    {
        $this->oAuthClient->setRegion($region);
        return $this;
    }

    public function getRegion(): string
    {
        return $this->oAuthClient->getRegion();
    }

    public function getUrl(): string
    {
        return $this->regionDomain[$this->getRegion()] ?? $this->regionDomain[Region::US];
    }

    public function getOrganizationId(): ?string
    {
        return $this->organizationId;
    }

    public function setOrganizationId(string $organizationId): self
    {
        $this->organizationId = $organizationId;
        return $this;
    }

    public function getList(string $url, array $filters = []): array
    {
        return $this->call($url, 'GET', [], ['query' => $filters]);
    }

    public function get(string $url, ?string $id = null, array $params = [])
    {
        if ($id !== null) {
            $url .= '/' . $id;
        }

        return $this->call(
            $url,
            'GET',
            [],
            $params
        );
    }

    /**
     * @throws ErrorResponseException
     */
    public function rawGet(string $url, ?array $params = []): StreamInterface
    {
        try {
            $response = $this->httpClient->get($url, $this->getHttpClientOptions([], $params));
            return $response->getBody();
        } catch (\InvalidArgumentException $e) {
            throw new ErrorResponseException('Response from Zoho is not success. Message: ' . $e);
        }
    }

    public function post(string $url, array $data = [], array $params = [])
    {
        return $this->call(
            $url,
            'POST',
            $data,
            $params
        );
    }

    public function put(string $url, ?string $id = null, array $data = [], array $params = [])
    {
        if ($id !== null) {
            $url .= '/' . $id;
        }

        return $this->call($url, 'PUT', $data, $params);
    }

    public function delete(string $url, ?string $id = null)
    {
        if ($id !== null) {
            $url .= '/' . $id;
        }

        return $this->call($url, 'DELETE');
    }

    public function call(string $uri, string $method, array $data = [], array $rawData = [])
    {
        $method = strtolower($method);
        try {
            $options = $this->getHttpClientOptions($data, $rawData);

            return $this->processResult(
                $this->httpClient->$method($this->getUrl() . $uri, $options)
            );
        } catch (ClientException $e) {
            // Retry?
            if ($e->getCode() === 401 && ! $this->retriedRefresh) {
                $this->oAuthClient->refreshAccessToken();
                $this->retriedRefresh = true;

                return $this->call($uri, $method, $data);
            }

            throw $e;
        }
    }

    protected function getHttpClientOptions(array $data = [], array $rawData = []): array
    {
        $json = ['JSONString' => json_encode($data)];

        return array_merge_recursive([
            'query'       => [
                'organization_id' => $this->getOrganizationId()
            ],
            'form_params' => $json,
            'headers'     => ['Authorization' => 'Zoho-oauthtoken ' . $this->oAuthClient->getAccessToken()]
        ], $rawData);
    }

    /**
     * @return array|string
     * @throws ErrorResponseException
     */
    protected function processResult(ResponseInterface $response)
    {
        // Update the API Limit variables if they have been returned.
        $this->orgRateLimit = (int)$response->getHeaderLine('X-Rate-Limit-Limit');
        $this->rateLimitRemaining = (int)$response->getHeaderline('X-Rate-Limit-Remaining');
        $this->rateLimitReset = (int)$response->getHeaderLine('X-Rate-Limit-Reset');

        try {
            $result = json_decode($response->getBody(), true);
        } catch (\InvalidArgumentException $e) {

            // All ok, probably not json, like PDF?
            if ($response->getStatusCode() >= 200 && $response->getStatusCode() <= 299) {
                return (string)$response->getBody();
            }

            throw new ErrorResponseException($response->getReasonPhrase(), $response->getStatusCode());
        }

        if (!$result) {
            // All ok, probably not json, like PDF?
            if ($response->getStatusCode() >= 200 && $response->getStatusCode() <= 299) {
                return (string)$response->getBody();
            }

            throw new ErrorResponseException($response->getReasonPhrase(), $response->getStatusCode());
        }

        if (isset($result['code']) && 0 == $result['code']) {
            return $result;
        }

        throw new ErrorResponseException('Response from Zoho is not success. Message: ' . $result['message'], $result['code'] ?? $response->getStatusCode());
    }

    /**
     * Return the rate limits for this org.
     *
     * These values are taken from the headers provided by Zoho
     * as of BUILD_VERSION "Dec_10_2019_23492". If these values
     * are not provided, or are invalid, they will be null
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

    public function getHttpClient(): ClientInterface
    {
        return $this->httpClient;
    }
}

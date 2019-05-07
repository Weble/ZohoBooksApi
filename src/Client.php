<?php

namespace Webleit\ZohoBooksApi;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Webleit\ZohoBooksApi\Exceptions\AuthException;
use Webleit\ZohoBooksApi\Exceptions\ErrorResponseException;

/**
 * Class Client
 * @see https://github.com/opsway/zohobooks-api
 * @package Webleit\ZohoBooksApi
 */
class Client
{
    /**
     *
     */
    const ENDPOINT_CN = 'https://books.zoho.com.cn/api/v3/';
    /**
     *
     */
    const ENDPOINT_EU = 'https://books.zoho.eu/api/v3/';
    /**
     *
     */
    const ENDPOINT_IN = 'https://books.zoho.in/api/v3/';
    /**
     *
     */
    const ENDPOINT_US = 'https://books.zoho.com/api/v3/';

    /**
     * @var string
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $authToken;

    /**
     * default organization id
     * @var string
     */
    protected $organizationId;

    /**
     * @var string
     */
    protected $region = 'US';

    /**
     * Client constructor.
     *
     * @param string|null $authToken
     * @param string|null $email
     * @param string|null $password
     */
    public function __construct($authToken = null, $email = null, $password = null)
    {
        $this->createClient();

        if (!$authToken) {
            $authToken = $this->auth($email, $password);
        }

        $this->authToken = $authToken;
    }

    /**
     * @param string $region
     * @return $this
     */
    public function setRegion($region = 'US')
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
            case 'CN':
                return self::ENDPOINT_CN;
                break;
            case 'IN':
                return self::ENDPOINT_IN;
                break;
            case 'EU':
                return self::ENDPOINT_EU;
                break;
            case 'US':
            default:
                return self::ENDPOINT_US;
                break;
        }
    }

    /**
     * @return string
     */
    public function getOrganizationId ()
    {
        return $this->organizationId;
    }

    /**
     * @return string
     */
    public function getRegion ()
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
                'query' => $this->getParams($organizationId, $data) + $params,
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
                'query' => $this->getParams($organizationId, $data) + $params,
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

        $params = [
            'authtoken' => $this->authToken,
            'organization_id' => $organizationId,
        ];

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
                'Authorization: Zoho-authtoken ' . $this->authToken
            ]
        ], $params);
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
     * @param string|null $email
     * @param string|null $password
     *
     * @throws AuthException
     *
     * @return string
     */
    private function auth($email, $password)
    {
        if (null === $email || null === $password) {
            throw new AuthException('Please provide authToken OR Email & Password for auto authentication.');
        }

        $response = $this->httpClient->post(
            'https://accounts.zoho.com/apiauthtoken/nb/create',
            [
                'form_params' => [
                    'SCOPE' => 'ZohoBooks/booksapi',
                    'EMAIL_ID' => $email,
                    'PASSWORD' => $password,
                ],
            ]
        );

        $authToken = '';

        if (preg_match('/AUTHTOKEN=(?<token>[a-z0-9]+)/', (string)$response->getBody(), $matches)) {
            $authToken = @$matches['token'];
        }

        return $authToken;
    }
}

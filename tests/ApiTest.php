<?php

namespace Webleit\ZohoBooksApi\Test;

use PHPUnit\Framework\TestCase;
use Weble\ZohoClient\OAuthClient;
use Webleit\ZohoBooksApi\ZohoBooks;
use Webleit\ZohoCrmApi\Client;
use Webleit\ZohoCrmApi\Exception\NonExistingModule;
use Webleit\ZohoCrmApi\Models\Request;
use Webleit\ZohoCrmApi\Models\Settings\Layout;
use Webleit\ZohoCrmApi\Models\Template;
use Webleit\ZohoCrmApi\Models\User;
use Webleit\ZohoCrmApi\Modules\Records;
use Webleit\ZohoCrmApi\ZohoCrm;

/**
 * Class ClassNameGeneratorTest
 * @package Webleit\ZohoBooksApi\Test
 */
class ApiTest extends TestCase
{
    /**
     * @var \Webleit\ZohoBooksApi\Client
     */
    protected static $client;

    /**
     * @var ZohoBooks
     */
    protected static $zoho;

    /**
     * setup
     */
    public static function setUpBeforeClass()
    {

        $authFile = __DIR__.'/config.example.json';
        if (file_exists(__DIR__.'/config.json')) {
            $authFile = __DIR__.'/config.json';
        }

        $auth = json_decode(file_get_contents($authFile));

        $client = new ZohoBooks($auth->client_id, $auth->client_secret);
        $client->setOrganizationId( $auth->organization_id);
        $client->getClient()->setRefreshToken($auth->refresh_token);

        self::$zoho = $client;
        self::$client = $client->getClient()->setRegion(OAuthClient::DC_US);
        self::$client = $client->getClient()->setRegion(OAuthClient::DC_US);
    }

    /**
     * @test
     */
    public function hasAccessToken()
    {
        $accessToken = self::$client->getAccessToken();
        $this->assertTrue(strlen($accessToken) > 0);
        $this->assertFalse(self::$client->accessTokenExpired());
    }

    /**
     * @test
     */
    public function canGetListOfOrganizations()
    {
        $list = self::$zoho->organizations->getList();
        $this->assertTrue(count($list) > 0);
    }

    /**
     * @test
     */
    public function canGetListOfContacts()
    {
        $list = self::$zoho->contacts->getList();
        $this->assertTrue(count($list) > 0);
    }

    /**
     * @test
     */
    public function canGetListOfCustomerPayments()
    {
        $list = self::$zoho->customerpayments->getList();
        $this->assertTrue(count($list) > 0);
    }
}
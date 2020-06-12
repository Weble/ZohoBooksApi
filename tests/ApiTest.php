<?php

namespace Webleit\ZohoBooksApi\Test;

use Cache\Adapter\Filesystem\FilesystemCachePool;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use PHPUnit\Framework\TestCase;
use Tightenco\Collect\Support\Collection;
use Weble\ZohoClient\Enums\Region;
use Weble\ZohoClient\OAuthClient;
use Webleit\ZohoBooksApi\Client;
use Webleit\ZohoBooksApi\Models\Contact;
use Webleit\ZohoBooksApi\Models\CustomerPayment;
use Webleit\ZohoBooksApi\ZohoBooks;

/**
 * Class ClassNameGeneratorTest
 * @package Webleit\ZohoBooksApi\Test
 */
class ApiTest extends TestCase
{
    /**
     * @var Client
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

        $auth = json_decode(file_get_contents($authFile))
        ;

        $oAuthClient = self::createOAuthClient();
        $client = new Client($oAuthClient);
        $client->setOrganizationId($auth->organization_id);

        $client = new ZohoBooks($client);

        self::$zoho = $client;
        self::$client = $client->getClient();
    }

    protected static function createOAuthClient(): OAuthClient
    {
        $authFile = __DIR__ . '/config.example.json';
        if (file_exists(__DIR__ . '/config.json')) {
            $authFile = __DIR__ . '/config.json';
        }

        $auth = json_decode(file_get_contents($authFile));

        $region = Region::us();
        if (isset($auth->region)) {
            $region = Region::make($auth->region);
        }

        $filesystemAdapter = new Local(sys_get_temp_dir());
        $filesystem        = new Filesystem($filesystemAdapter);
        $pool = new FilesystemCachePool($filesystem);

        $client = new OAuthClient($auth->client_id, $auth->client_secret);
        $client->setRefreshToken($auth->refresh_token);
        $client->setRegion($region);
        $client->offlineMode();
        $client->useCache($pool);

        return $client;
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
        /** @var Collection $list */
        $list = self::$zoho->customerpayments->getList();
        $this->assertTrue(count($list) > 0);

        /** @var CustomerPayment $item */
        $item = $list->first();

        $itemRetrieved = self::$zoho->customerpayments->get($item->getId());

        $this->assertEquals(CustomerPayment::class, get_class($itemRetrieved));
        $this->assertEquals($item->getId(), $itemRetrieved->getId());
    }

    /**
     * @test
     */
    public function canCreateAndDeleteCustomer()
    {
        $name = 'Test ' . uniqid();
        /** @var Contact $customer */
        $customer = self::$zoho->contacts->create([
            'contact_name' => $name
        ]);

        $this->assertEquals(Contact::class, get_class($customer));
        $this->assertEquals($name, $customer->toArray()['contact_name']);

        $this->assertTrue(self::$zoho->contacts->delete($customer->getId()));
    }

    /**
     * @test
     */
    public function canCreateAndFilterCustomer()
    {
        $name = 'Test ' . uniqid();
        /** @var Contact $customer */
        $customer = self::$zoho->contacts->create([
            'contact_name' => $name
        ]);

        $customers = self::$zoho->contacts->getList([
            'contact_name' => $name
        ]);

        $this->assertEquals(1, $customers->count());

        // delete it it afterwards
        self::$zoho->contacts->delete($customer->getId());
    }
}

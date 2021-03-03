<?php

namespace Webleit\ZohoBooksApi\Tests;

use Cache\Adapter\Filesystem\FilesystemCachePool;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Weble\ZohoClient\Enums\Region;
use Weble\ZohoClient\OAuthClient;
use Webleit\ZohoBooksApi\Client;
use Webleit\ZohoBooksApi\ZohoBooks;

abstract class TestCase extends \PHPUnit\Framework\TestCase
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
    public static function setUpBeforeClass(): void
    {

        $authFile = __DIR__.'/config.example.json';
        if (file_exists(__DIR__.'/config.json')) {
            $authFile = __DIR__.'/config.json';
        }

        $auth = json_decode(file_get_contents($authFile));

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

        $region = Region::US;
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
}

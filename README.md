[![Latest Version on Packagist](https://img.shields.io/packagist/v/webleit/zohobooksapi.svg?style=flat-square)](https://packagist.org/packages/webleit/zohobooksapi)
[![Total Downloads](https://img.shields.io/packagist/dt/webleit/zohobooksapi.svg?style=flat-square)](https://packagist.org/packages/webleit/zohobooksapi)

# Zoho Books API v3 - PHP SDK

This Library is a SDK in PHP that simplifies the usage of the Zoho Books Api version 3 (https://www.zoho.com/books/api/v3/)
It provides both an interface to ease the interaction with the APIs without bothering with the actual REST request, while packaging the various responses using very simple Model classes that can be then uses with any other library or framework.

## Installation 

```
composer require webleit/zohobooksapi
```

## Usage

In order to use the library, just require the composer autoload file, and then fire up the library itself.
In order for the library to work, you need to be authenticated with the zoho apis.

## Online Mode

```php
require './vendor/autoload.php';

// setup the generic zoho oath client
$oAuthClient = new \Weble\ZohoClient\OAuthClient('[CLIENT_ID]', '[CLIENT_SECRET]');
$oAuthClient->setRefreshToken('[REFRESH_TOKEN]');
$oAuthClient->setRegion(\Weble\ZohoClient\Enums\Region::us());
$oAuthClient->useCache($yourPSR6CachePool);

// setup the zoho books client
$client = new \Webleit\ZohoBooksApi\Client($oAuthClient);
$client->setOrganizationId('[YOUR_ORGANIZATION_ID]');

// Create the main class
$zohoBooks = new \Webleit\ZohoBooksApi\ZohoBooks($client);
```

## Offline Mode

This one is preferred when you need to autonomously renew the access token yourself. Used in all the "machine to machine" communication, and it's the best way when you are using the apis to, for example, sync with a 3rd party application, like your ERP or Ecommerce website. See https://github.com/Weble/ZohoClient#example-usage-offline-mode for more details on this. 

```php
require './vendor/autoload.php';

// setup the generic zoho oath client
$oAuthClient = new \Weble\ZohoClient\OAuthClient('[CLIENT_ID]', '[CLIENT_SECRET]');
$oAuthClient->setRefreshToken('[REFRESH_TOKEN]');
$oAuthClient->setRegion(\Weble\ZohoClient\Enums\Region::us());
$oAuthClient->useCache($yourPSR6CachePool);
$oAuthClient->offlineMode();

// Access Token
$accessToken = $oAuthClient->getAccessToken();
$isExpired = $oAuthClient->accessTokenExpired();

// setup the zoho books client
$client = new \Webleit\ZohoBooksApi\Client($oAuthClient);
$client->setOrganizationId('[YOUR_ORGANIZATION_ID]');

// Create the main class
$zohoBooks = new \Webleit\ZohoBooksApi\ZohoBooks($client);
```

## API calls

To call any Api, just use the same name reported in the api docs.
You can get the list of supported apis using the getAvailableModules() method

```php
$zohoBooks->getAvailableModules();
```

You can, for example, get the list of invoices by using:

```php
$invoices = $zohoBooks->invoices->getList();
```

or the list of contacts

```php
$contacts = $zohoBooks->contacts->getList();
```

### List calls

To get a list of resources from a module, use the getList() method

```php
$invoices = $zohoBooks->invoices->getList();
```

It's possible to pass through some parameters to filter the result (see the zoho books api docs for some examples)

```php
$invoices = $zohoBooks->invoices->getList(['status' => 'unpaid']);
```

In order to navigate the pages, just use the "page" and "per_page" parameters in the getList call

```php
$invoices = $zohoBooks->invoices->getList(['status' => 'unpaid', 'page' => 3, 'per_page' => 200]);
```


## Return Types

Any "list" api call returns a Collection object, which is taken for Laravel Collection package.
You can therefore use the result as Collection, which allows mapping, reducing, serializing, etc

```php
$invoices = $zohoBooks->invoices->getList();

$data = $invoices->toArray();
$json = $invoices->toJson();

// After fetch filtering in php
$filtered = $invoices->where('total', '>', 200);

// Grouping
$filtered = $invoices->groupBy('customer_id');

```

Any "resource" api call returns a Model object of a class dedicated to the single resource you're fetching.
For example, calling

```php
$invoice = $zohoBooks->invoices->get('idoftheinvoice');
$id = $invoice->getId();
$data = $invoice->toArray();
$total = $invoice->total;

```

will return a \Webleit\ZohoBooksApi\Models\Invoice object, which is Arrayable and Jsonable, and that can be therefore used in many ways.

## Contributing

Finding bugs, sending pull requests or improving the docs - any contribution is welcome and highly appreciated

## Versioning

Semantic Versioning Specification (SemVer) is used.

## Copyright and License

Copyright Weble Srl under the MIT license.

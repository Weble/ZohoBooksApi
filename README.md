# Zoho Books API v3 - PHP SDK

This Library is a SDK in PHP that simplifies the usage of the Zoho Books Api version 3 (https://www.zoho.com/books/api/v3/)
It provides both an interface to ease the interaction with the APIs without bothering with the actual REST request, while packaging the various responses using very simple Model classes that can be then uses with any other library or framework.

## Installation 

TODO

## Usage

In order to use the library, just require the composer autoload file, and then fire up the library itself.
In order for the library to work, you need to provide an auth token for the zoho book apis.

```php
require './vendor/autoload.php';
$zohoBooks = new \Webleit\ZohoBooksApi\ZohoBooks('authtoken');
```

If you have more than one organization in the account, you can specify the organization id as the second parameter:

```php
$zohoBooks = new \Webleit\ZohoBooksApi\ZohoBooks('authtoken', 'organization_id');
```


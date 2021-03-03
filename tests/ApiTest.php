<?php

namespace Webleit\ZohoBooksApi\Tests;

use Illuminate\Support\Collection;
use Webleit\ZohoBooksApi\Models\Contact;
use Webleit\ZohoBooksApi\Models\CustomerPayment;

/**
 * Class ClassNameGeneratorTest
 * @package Webleit\ZohoBooksApi\Test
 */
class ApiTest extends TestCase
{

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

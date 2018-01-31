<?php

namespace Webleit\ZohoBooksApi\Test;

use PHPUnit\Framework\TestCase;
use Webleit\ZohoBooksApi\Contracts\ProvidesModules;
use Webleit\ZohoBooksApi\Models\CustomerPayment;
use Webleit\ZohoBooksApi\Modules\Module;
use Webleit\ZohoBooksApi\ZohoBooks;

/**
 * Class ClassNameGeneratorTest
 * @package Webleit\ZohoBooksApi\Test
 */
class ClassNameGeneratorTest extends TestCase
{
    /**
     * @test
     */
    public function generate_class_names()
    {
        // Create a stub for the class.
        $zohoBooks = new ZohoBooks('FAKE_AUTH_TOKEN', 'FAKE_ORG_ID');

        $this->assert_that_provided_modules_exists($zohoBooks);
    }

    /**
     * @test
     */
    public function customer_payments_exists()
    {
        // Create a stub for the class.
        $zohoBooks = new ZohoBooks('FAKE_AUTH_TOKEN', 'FAKE_ORG_ID');
        $customerPayments = $zohoBooks->customerpayments;
        $customerPayment = $customerPayments->make([]);

        $this->assertEquals( get_class($customerPayment),CustomerPayment::class);
    }

    /**
     * @param ProvidesModules $class
     */
    protected function assert_that_provided_modules_exists(ProvidesModules $class)
    {
        foreach ($class->getAvailableModules() as $module) {
            /** @var Module $moduleClass */
            $moduleClass = $class->createModule($module);

            if ($moduleClass instanceof Module) {
                $this->assertTrue(class_exists($moduleClass->getModelClassName()), $moduleClass->getModelClassName());
            }

            if ($moduleClass instanceof ProvidesModules) {
                $this->assert_that_provided_modules_exists($moduleClass);
            }
        }
    }
}
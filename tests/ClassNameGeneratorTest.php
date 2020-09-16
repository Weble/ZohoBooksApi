<?php

namespace Webleit\ZohoBooksApi\Tests;

use Webleit\ZohoBooksApi\Contracts\ProvidesModules;
use Webleit\ZohoBooksApi\Models\CustomerPayment;
use Webleit\ZohoBooksApi\Modules\Import;
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
        $this->assert_that_provided_modules_exists(self::$zoho);
    }

    /**
     * @test
     */
    public function customer_payments_exists()
    {
        // Create a stub for the class.
        $zohoBooks = self::$zoho;
        $customerPayments = $zohoBooks->customerpayments;
        $customerPayment = $customerPayments->make([]);

        $this->assertEquals( get_class($customerPayment),CustomerPayment::class);
    }

    /**
     * @param ProvidesModules $class
     */
    protected function assert_that_provided_modules_exists(ProvidesModules $class)
    {
        foreach ($class->getAvailableModuleKeys() as $module) {

            /** @var Module $moduleClass */
            $moduleClass = $class->createModule($module);

            $this->assertTrue($moduleClass instanceof \Webleit\ZohoBooksApi\Contracts\Module, $module);

            if ($moduleClass instanceof Module && $moduleClass !== Import::class) {
                $this->assertTrue(class_exists($moduleClass->getModelClassName()), $moduleClass->getModelClassName());
            }

            if ($moduleClass instanceof ProvidesModules) {
                $this->assert_that_provided_modules_exists($moduleClass);
            }
        }
    }
}

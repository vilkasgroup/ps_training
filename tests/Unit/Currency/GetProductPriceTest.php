<?php

namespace Invertus\PsTraining\Tests\Unit\Currency;

use Invertus\Training\Price\GetProductPrice;
use Module;
use PHPUnit\Framework\TestCase;

class GetProductPriceTest extends TestCase
{
    /**
     * Example of a test. For propering testing we require to have phpunit.xml and bootstrap which loads
     * config.inc.php file and modules autoload.php file
     */
    public function test_getting_of_product_price()
    {
        $getProductPrice = new GetProductPrice(Module::getInstanceByName('training'));
        $this->assertEquals('Message', $getProductPrice->getPrice(2));
    }
}

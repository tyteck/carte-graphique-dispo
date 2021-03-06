<?php

namespace Tests\Unit;

use App\Exceptions\ProductNotFoundException;
use App\Shop\LDLC;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class LDLCTest extends TestCase
{
    use RefreshDatabase;

    public const ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI = 'PB00394604';
    public const AVAILABLE_PRODUCT = 'PB00387288';

    public function setUp():void
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'ShopSeeder']);
    }

    public function testProductNameIsOk()
    {
        $this->assertEquals(
            'ASUS GeForce ROG STRIX RTX 3060 Ti O8G GAMING',
            LDLC::get(self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI)->productName()
        );
    }

    public function testInvalidProductShouldThrow()
    {
        $this->expectException(ProductNotFoundException::class);
        LDLC::get('product-that-will-never-exists');
    }

    public function testProductPriceIsOk()
    {
        $factory = LDLC::get(self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI);
        $this->assertEquals(
            '829.96',
            $factory->productPrice(),
            "Price for {$factory->productPageUrl()} has changed ?"
        );
    }

    public function testProductAvailableIsOk()
    {
        $this->assertFalse(LDLC::get(self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI)->productAvailable());
        $this->assertTrue(LDLC::get(self::AVAILABLE_PRODUCT)->productAvailable());
    }
}

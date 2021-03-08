<?php

namespace Tests\Unit;

use App\Crawlers\TopAchat;
use App\Exceptions\ProductNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class TopAchatTest extends TestCase
{
    use RefreshDatabase;

    public const ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI = '20006243';
    public const AVAILABLE_PRODUCT = '10095283';

    public function setUp():void
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'ShopSeeder']);
    }

    public function testInvalidProductShouldThrow()
    {
        $this->expectException(ProductNotFoundException::class);
        TopAchat::get('product-that-will-never-exists');
    }

    public function testProductNameIsOk()
    {
        $this->assertEquals(
            'Asus GeForce RTX 3060 Ti ROG STRIX O8G GAMING',
            TopAchat::get(self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI)->productName()
        );
    }

    public function testProductPriceIsOk()
    {
        $factory = TopAchat::get(self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI);
        $this->assertEquals(
            '829.99',
            $factory->productPrice(),
            "Price for {$factory->productPageUrl()} has changed ?"
        );
    }

    public function testProductAvailableIsOk()
    {
        $this->assertFalse(TopAchat::get(self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI)->productAvailable());
        $this->assertTrue(TopAchat::get(self::AVAILABLE_PRODUCT)->productAvailable());
    }

    /** @test */
    public function product_chipset_is_ok()
    {
        $factory = TopAchat::get(self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI);
        $this->assertEquals(
            'RTX 3060 Ti',
            $factory->productChipset(),
            "Chipset for {$factory->productPageUrl()} has changed ?"
        );
        /** one RTX 3080 */
        $factory = TopAchat::get('20005911');
        $this->assertEquals(
            'RTX 3080',
            $factory->productChipset(),
            "Chipset for {$factory->productPageUrl()} has changed ?"
        );
    }
}

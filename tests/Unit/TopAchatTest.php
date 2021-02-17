<?php

namespace Tests\Unit;

use App\Exceptions\ProductNotFoundException;
use App\Shop\TopAchat;
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
            '799.99',
            $factory->productPrice(),
            "Price for {$factory->productPageUrl()} has changed ?"
        );
    }

    public function testProductAvailableIsOk()
    {
        $this->assertFalse(TopAchat::get(self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI)->productAvailable());
        $this->assertTrue(TopAchat::get(self::AVAILABLE_PRODUCT)->productAvailable());
    }

    public function testProductPageUrl()
    {
        $this->assertEquals(
            'https://www.topachat.com/pages/detail2_cat_est_micro_puis_rubrique_est_wgfx_pcie_puis_ref_est_in' . self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI . '.html',
            TopAchat::get(self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI)->productPageUrl()
        );
    }
}

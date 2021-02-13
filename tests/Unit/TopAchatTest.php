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

    public const ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI = 'pages/detail2_cat_est_micro_puis_rubrique_est_wgfx_pcie_puis_ref_est_in20006243.html';
    public const AVAILABLE_PRODUCT = 'pages/detail2_cat_est_gaming_puis_rubrique_est_wg_pcsou_puis_ref_est_in10114919.html';

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
        $this->assertEquals(
            '699.99',
            TopAchat::get(self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI)->productPrice()
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
            'https://www.topachat.com/' . self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI,
            TopAchat::get(self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI)->productPageUrl()
        );
    }
}

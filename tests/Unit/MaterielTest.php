<?php

namespace Tests\Unit;

use App\Exceptions\ProductNotFoundException;
use App\Shop\Materiel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class MaterielTest extends TestCase
{
    use RefreshDatabase;

    public const ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI = '202011040047';
    public const AVAILABLE_PRODUCT = '201909300069';

    public function setUp():void
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'ShopSeeder']);
    }

    public function testProductNameIsOk()
    {
        $this->assertEquals(
            'Asus GeForce RTX 3060 Ti ROG STRIX OC',
            Materiel::get(self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI)->productName()
        );
    }

    public function testInvalidProductShouldThrow()
    {
        $this->expectException(ProductNotFoundException::class);
        Materiel::get('product-that-will-never-exists');
    }

    public function testProductPriceIsOk()
    {
        $factory = Materiel::get(self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI);
        $this->assertEquals(
            '829.96',
            $factory->productPrice(),
            "Price for {$factory->productPageUrl()} has changed ?"
        );
    }

    public function testProductAvailableIsOk()
    {
        $this->assertFalse(Materiel::get(self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI)->productAvailable());
        $this->assertTrue(Materiel::get(self::AVAILABLE_PRODUCT)->productAvailable());
    }
}

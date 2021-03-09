<?php

namespace Tests\Unit;

use App\Exceptions\ProductNotFoundException;
use App\Crawlers\Materiel;
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
        $this->markTestSkipped('Product prices are changing too frequently 😤 to be tested properly.');
        $factory = Materiel::get(self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI);
        $this->assertEquals(
            '849.95',
            $factory->productPrice(),
            "Price for {$factory->productPageUrl()} has changed ?"
        );
    }

    /** @test */
    public function product_chipset_is_ok()
    {
        $factory = Materiel::get(self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI);
        $this->assertEquals(
            'RTX 3060 Ti',
            $factory->productChipset(),
            "Chipset for {$factory->productPageUrl()} has changed ?"
        );
        /** one RTX 3080 */
        $factory = Materiel::get('202009080083');
        $this->assertEquals(
            'RTX 3080',
            $factory->productChipset(),
            "Chipset for {$factory->productPageUrl()} has changed ?"
        );
    }

    public function testProductAvailableIsOk()
    {
        $this->assertFalse(Materiel::get(self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI)->productAvailable());
        $this->assertTrue(Materiel::get(self::AVAILABLE_PRODUCT)->productAvailable());
    }
}

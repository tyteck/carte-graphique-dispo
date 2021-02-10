<?php

namespace Tests\Unit;

use App\Exceptions\ProductNotFoundException;
use App\Shop\Materiel;
use Tests\TestCase;

class MaterielTest extends TestCase
{
    public const ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI = 'produit/202011040047.html';
    public const AVAILABLE_PRODUCT = 'produit/201909300069.html';

    public function setUp():void
    {
        parent::setUp();
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
        $this->assertEquals(
            '719.95',
            Materiel::get(self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI)->productPrice()
        );
    }

    public function testProductAvailableIsOk()
    {
        $this->assertFalse(Materiel::get(self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI)->productAvailable());
        $this->assertTrue(Materiel::get(self::AVAILABLE_PRODUCT)->productAvailable());
    }

    public function testProductPageUrl()
    {
        $this->assertEquals(
            'https://www.materiel.net/' . self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI,
            Materiel::get(self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI)->productPageUrl()
        );
    }
}

<?php

namespace Tests\Unit;

use App\Exceptions\ProductNotFoundException;
use App\Shop\LDLC;
use Tests\TestCase;

class LDLCTest extends TestCase
{
    public const ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI = 'fiche/PB00394604.html';
    public const AVAILABLE_PRODUCT = 'fiche/PB00387288.html';

    public function setUp():void
    {
        parent::setUp();
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
        $this->assertEquals(
            '719.95',
            LDLC::get(self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI)->productPrice()
        );
    }

    public function testProductAvailableIsOk()
    {
        $this->assertFalse(LDLC::get(self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI)->productAvailable());
        $this->assertTrue(LDLC::get(self::AVAILABLE_PRODUCT)->productAvailable());
    }

    public function testProductPageUrl()
    {
        $this->assertEquals(
            'https://www.ldlc.com/' . self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI,
            LDLC::get(self::ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI)->productPageUrl()
        );
    }
}

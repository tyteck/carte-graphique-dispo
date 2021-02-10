<?php

namespace Tests\Unit;

use App\Shop\LDLC;
use PHPUnit\Framework\TestCase;

class LDLCTest extends TestCase
{
    public const ASUS_GeForce_ROG_STRIX_RTX_3060_Ti = 'fiche/PB00394604.html';

    public function setUp():void
    {
        parent::setUp();
    }

    public function testProductNameIsOk()
    {
        $this->assertEquals(
            'ASUS GeForce ROG STRIX RTX 3060 Ti O8G GAMING',
            LDLC::get('fiche/PB00394604.html')->productName()
        );
    }

    public function testProductPriceIsOk()
    {
        $this->assertEquals(
            '719,95',
            LDLC::get('fiche/PB00394604.html')->productPrice()
        );
    }

    public function testProductAvailableIsOk()
    {
        //$this->assertFalse(LDLC::get('fiche/PB00394604.html')->productAvailable());
        $this->assertTrue(LDLC::get('fiche/PB00387288.html')->productAvailable());
    }
}

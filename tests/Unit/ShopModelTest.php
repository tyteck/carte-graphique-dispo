<?php

namespace Tests\Unit;

use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopModelTest extends TestCase
{
    use RefreshDatabase;

    /** @var \App\Models\Shop $shop */
    protected $shop;

    public function setUp():void
    {
        parent::setUp();
        $this->shop = Shop::factory()->create(
            [
                'base_url' => 'https://www.ldlc.com/fiche/{{PRODUCT_ID}}.html',
                'slug' => 'ldlc'
            ]
        );
    }

    public function testProductUrlIsOk()
    {
        $this->assertEquals(
            'https://www.ldlc.com/fiche/prod12x.html',
            $this->shop->productPageUrl('prod12x')
        );
    }
}

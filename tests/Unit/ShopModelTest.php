<?php

namespace Tests\Unit;

use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use LogicException;
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
                'product_page_url' => 'https://www.ldlc.com/fiche/{{PRODUCT_ID}}.html',
                'slug' => 'ldlc'
            ]
        );
    }

    /** @test */
    public function product_url_throw_exception_when_product_page_url_empty()
    {
        $invalidShop = Shop::factory()->create(
            [
                'product_page_url' => '',
                'slug' => 'ldlc'
            ]
        );
        $this->expectException(LogicException::class);
        $invalidShop->productPageUrl('should_throw_exception');
    }

    /** @test */
    public function product_url_throw_exception_when_product_id_is_empty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->shop->productPageUrl('');
    }

    /** @test */
    public function product_url_is_ok()
    {
        $this->assertEquals(
            'https://www.ldlc.com/fiche/prod12x.html',
            $this->shop->productPageUrl('prod12x')
        );
    }
}

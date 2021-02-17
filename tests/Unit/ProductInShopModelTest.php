<?php

namespace Tests\Unit;

use App\Models\Card;
use App\Models\ProductInShop;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ProductInShopModelTest extends TestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'ShopSeeder']);
    }

    public function testGetCrawlerIsOk()
    {
        $productInShop = ProductInShop::factory()
            ->for(Shop::bySlug('ldlc'))
            ->for(Card::bySlug('msi-geforce-rtx-3060-ti-ventus-2x-oc'))
            ->create(['in_shop_product_id' => 'test']);
        dd($productInShop);
        //$this->assertInstanceOf()
    }
}

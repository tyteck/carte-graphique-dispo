<?php

namespace Tests\Unit;

use App\Exceptions\OnlineShopUnknownException;
use App\Helpers\ExtractDataFromUrl;
use App\Models\Card;
use App\Models\Chipset;
use App\Models\Shop;
use InvalidArgumentException;
use Tests\TestCase;

class ExtractDataFromUrlTest extends TestCase
{
    public function setUp() :void
    {
        parent::setUp();
        $this->shop = Shop::factory()->create(
            [
                'domain_name' => 'www.ldlc.com',
                'product_page_url' => 'fiche/{{PRODUCT_ID}}.html',
                'slug' => 'ldlc'
            ]
        );
    }

    /** @test */
    public function invalid_url_throw_exception()
    {
        $this->expectException(InvalidArgumentException::class);
        ExtractDataFromUrl::from('this is not an url');
    }

    /** @test */
    public function unknown_shop_throw_exception()
    {
        $this->expectException(OnlineShopUnknownException::class);
        ExtractDataFromUrl::from('https://www.aliexpress.com/item/1005002235665935.html');
    }

    /** @test */
    public function valid_shop_is_ok()
    {
        $factory = ExtractDataFromUrl::from('https://www.ldlc.com/fiche/PB00385046.html?I-dont-care=about-the-query');
        $this->assertNotNull(
            $factory->shop(),
            'We should have identified LDLC shop from this url.'
        );

        $this->assertInstanceOf(
            Shop::class,
            $factory->shop(),
            'LDLC should have been identified and class should have return Shop Model.'
        );

        $this->assertEquals(
            $this->shop->id,
            $factory->shop()->id,
            "We were expecting LDLC {$this->shop->id} and we obtained something else."
        );
    }

    /** @test */
    public function valid_card_is_ok()
    {
        $card = Card::factory()->for(Chipset::factory())->create(['slug' => 'test']);
        $productId = 'PB00394053';
        $card->addItInShopWithId($this->shop, $productId);
        $factory = ExtractDataFromUrl::from("https://www.ldlc.com/fiche/{$productId}.html?I-dont-care=about-the-query");
        $this->assertNotNull($factory->card());
        $this->assertInstanceOf(Card::class, $factory->card());
        $this->markTestIncomplete('➕ to do ➕');
    }
}

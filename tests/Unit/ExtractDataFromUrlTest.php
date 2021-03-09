<?php

namespace Tests\Unit;

use App\Exceptions\ChipsetUnknownException;
use App\Exceptions\OnlineShopUnknownException;
use App\Jobs\ExtractDataFromUrl;
use App\Models\Card;
use App\Models\Chipset;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Tests\TestCase;

class ExtractDataFromUrlTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() :void
    {
        parent::setUp();
        $this->shop = $this->createLdlc();
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
    public function unknown_chipset_throw_exception()
    {
        /** no chipset in db yet */
        $this->expectException(ChipsetUnknownException::class);
        ExtractDataFromUrl::from('https://www.ldlc.com/fiche/PB00385046.html?I-dont-care=about-the-query');
    }

    /** @test */
    public function valid_shop_is_ok()
    {
        /** some RTX 3070 card */
        $productId = 'PB00385046';
        Chipset::factory()->create(['slug' => 'rtx-3070']);

        $factory = ExtractDataFromUrl::from("https://www.ldlc.com/fiche/{$productId}.html?I-dont-care=about-the-query");

        $this->assertNotNull($factory->shop(), 'We should have identified LDLC shop from this url.');
        $this->assertInstanceOf(Shop::class, $factory->shop());
        $this->assertEquals($this->shop->id, $factory->shop()->id, "We were expecting LDLC {$this->shop->id} and we obtained something else.");
    }

    /** @test */
    public function known_card_is_ok()
    {
        /** creating a card for the need of the test with some productId */
        $card = $this->createCardWithSlug('test');
        $productId = 'PB00394053';
        $card->addItInShopWithId($this->shop, $productId);

        $factory = ExtractDataFromUrl::from("https://www.ldlc.com/fiche/{$productId}.html?I-dont-care=about-the-query");
        $this->assertNotNull($factory->card());
        $this->assertInstanceOf(Card::class, $factory->card());
        $this->assertEquals(
            $card->id,
            $factory->card()->id,
            "We were expecting Card {$card->id} and we obtained something else."
        );
    }

    /** @test */
    public function unknown_card_should_create_card_with_shop()
    {
        $expectedChipset = 'RTX 3060 Ti';
        $chipset = Chipset::factory()->create(
            [
                'name' => $expectedChipset,
                'slug' => Str::slug($expectedChipset),
            ]
        );

        $productId = 'PB00394053';
        $factory = ExtractDataFromUrl::from("https://www.ldlc.com/fiche/{$productId}.html?I-dont-care=about-the-query");
    }

    /** @test */
    public function init_crawler_is_ok()
    {
        //code
    }
}

<?php

namespace Tests\Unit;

use App\Models\Card;
use App\Models\Chipset;
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
                'domain_name' => 'www.ldlc.com',
                'product_page_url' => 'fiche/{{PRODUCT_ID}}.html',
                'slug' => 'ldlc'
            ]
        );
    }

    /** @test */
    public function by_slug_is_ok()
    {
        $this->assertNull(Shop::bySlug('unknown-slug'));

        $this->assertEquals($this->shop->id, Shop::bySlug($this->shop->slug)->id);
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

    /** @test */
    public function by_domain_is_ok()
    {
        $this->assertNull(Shop::byDomain('www.unknown-domaine.name'));

        $this->assertEquals($this->shop->id, Shop::byDomain($this->shop->domain_name)->id);
    }

    /** @test */
    public function card_by_product_id_is_ok()
    {
        /** should return null */
        $this->assertNull($this->shop->cardByProductId('this-product-id-will-never-exist'));

        $expectedProductId = 'PB00394053';

        /** creating a card */
        $card = Card::factory()->for(Chipset::factory())->create(['slug' => 'test']);

        /** adding a card to some shop (not the one we are using) */
        $card->addItInShopWithId(Shop::factory()->create(), $expectedProductId);
        $this->assertNull($this->shop->cardByProductId($expectedProductId));

        /** adding a card to the shop */
        $card->addItInShopWithId($this->shop, $expectedProductId);

        /** we should find this card */
        $cardFromProductId = $this->shop->cardByProductId($expectedProductId);
        $this->assertNotNull($cardFromProductId);
        $this->assertInstanceOf(Card::class, $cardFromProductId);
        $this->assertEquals($card->id, $cardFromProductId->id);
    }

    /** @test */
    public function card_with_product_id_exists_is_ok()
    {
        /** should return null */
        $this->assertFalse($this->shop->cardWithProductIdExists('this-product-id-will-never-exist'));

        $expectedProductId = 'PB00394053';

        /** creating a card */
        $card = Card::factory()->for(Chipset::factory())->create(['slug' => 'test']);

        /** adding a card to some shop (not the good one) */
        $card->addItInShopWithId(Shop::factory()->create(), $expectedProductId);
        $this->assertFalse($this->shop->cardWithProductIdExists($expectedProductId));

        /** adding it to the real shop */
        $card->addItInShopWithId($this->shop, $expectedProductId);

        /** we should find this card */
        $this->assertTrue($this->shop->cardWithProductIdExists($expectedProductId));
    }
}

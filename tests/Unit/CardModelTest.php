<?php

namespace Tests\Unit;

use App\Models\Card;
use App\Models\Chipset;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CardModelTest extends TestCase
{
    use RefreshDatabase;

    /** @var \App\Models\Card $card */
    protected $card;

    /** @var \App\Models\Shop $shop */
    protected $shop;

    public function setUp():void
    {
        parent::setUp();
        $this->card = Card::factory()
            ->for(Chipset::factory())
            ->create(['slug' => 'test']);
        $this->shop = Shop::factory()->create(
            [
                'slug' => 'ldlc',
                'product_page_url' => 'https://www.ldlc.com/fiche/{{PRODUCT_ID}}.html',
            ]
        );
    }

    public function testingBySlug()
    {
        $this->assertNull(Card::bySlug('do not exist'));

        $this->assertNotNull(Card::bySlug('test'));
        $this->assertInstanceOf(Card::class, Card::bySlug('test'));
        $this->assertEquals($this->card->id, Card::bySlug('test')->id);
    }

    public function testIsCardInShopIsOk()
    {
        $this->assertFalse($this->card->isCardInShop($this->shop));
        $this->card->addItInShopWithId($this->shop, 'prod12x');
        $this->assertTrue($this->card->isCardInShop($this->shop));
    }

    public function testProductIdInShopIsOk()
    {
        $this->assertNull($this->card->productIdInShop($this->shop));

        $this->card->addItInShopWithId($this->shop, 'prod12x');
        $this->card->refresh();
        $this->assertEquals('prod12x', $this->card->productIdInShop($this->shop));
    }

    public function testProductUrlIsOk()
    {
        $this->assertNull($this->card->productUrlForShop($this->shop));

        $this->card->addItInShopWithId($this->shop, 'prod12x');
        $this->card->refresh();

        $this->assertEquals(
            'https://www.ldlc.com/fiche/prod12x.html',
            $this->card->productUrlForShop($this->shop)
        );
    }
}

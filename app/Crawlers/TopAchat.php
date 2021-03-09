<?php

namespace App\Crawlers;

use App\Exceptions\ChipsetNotFoundException;
use App\Exceptions\PriceNotFoundException;
use App\Exceptions\ProductNotFoundException;
use App\Exceptions\UnknownShopException;
use App\Interfaces\Crawler;
use App\Models\Shop;
use App\Traits\Parser;

class TopAchat implements Crawler
{
    use Parser;

    /** @var \App\Models\Shop $shop */
    protected $shop;

    /** @var string $productId */
    protected $productId;

    /** @var \Symfony\Component\DomCrawler\Crawler $crawler*/
    protected $crawler ;

    private function __construct(string $productId)
    {
        $this->shop = Shop::bySlug('top-achat');
        if ($this->shop === null) {
            throw new UnknownShopException('This shop ' . self::class . ' is unknown.');
        }
        $this->productId = $productId;
        $this->crawler = $this->parse($this->productPageUrl());
        $this->check();
    }

    public static function get(string $productId): Crawler
    {
        return new static($productId);
    }

    public function check()
    {
        if (!$this->crawler->evaluate('//body[@class="detail2"]')->count()) {
            throw new ProductNotFoundException(self::class . " Product on this page {$this->productPageUrl()} does not exist.");
        }
    }

    public function productPrice(): ?string
    {
        $result = $this->crawler->evaluate('//section[@id="panier"]/div[@class="prix"]/div[@class="eproduct NOR"]/span[1]');
        if ($result->count()) {
            if (preg_match('/(?P<price>[0-9.]*)/', $result->text(), $matches)) {
                return($matches['price']);
            }
        }
        throw new PriceNotFoundException(self::class . " Cannot found price for {$this->productPageUrl()}");
    }

    public function productName(): ?string
    {
        return $this->crawler->evaluate('//div[@class="libelle"]/h1[@class="fn"]')->text();
    }

    public function productAvailable(): bool
    {
        return $this->crawler->evaluate('//section[@id="panier"][@class="cart-box en-stock"]')->count() > 0;
    }

    public function productChipset(): string
    {
        if (preg_match('/GeForce (?P<chipset>RTX [0-9]{4}( Ti|-Super)?)/', $this->productName(), $matches)) {
            return $matches['chipset'];
        }
        throw new ChipsetNotFoundException(self::class . " Cannot found chipset for {$this->productPageUrl()}");
    }

    public function productPageUrl():string
    {
        return $this->shop->productPageUrl($this->productId);
    }
}

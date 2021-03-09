<?php

namespace App\Crawlers;

use App\Exceptions\ChipsetNotFoundException;
use App\Exceptions\PriceNotFoundException;
use App\Exceptions\ProductNotFoundException;
use App\Exceptions\UnknownShopException;
use App\Interfaces\Crawler;
use App\Models\Shop;
use App\Traits\Parser;

class Materiel implements Crawler
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
        $this->shop = Shop::bySlug('materielnet');
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
        if ($this->crawler->evaluate('//section[@class="c-site__error"]')->count() > 0) {
            throw new ProductNotFoundException(self::class . " Product on this page {$this->productPageUrl()} does not exist.");
        }
        return $this;
    }

    public function productPrice(): ?string
    {
        $result = $this->crawler->evaluate('//span[@class="o-product__price"]');
        if ($result->count()) {
            return str_replace('€', '.', $this->crawler->evaluate('//span[@class="o-product__price"]')->text());
        }
        throw new PriceNotFoundException(self::class . " Cannot found price for {$this->productPageUrl()}");
    }

    public function productName(): ?string
    {
        return $this->crawler->evaluate('//div[@class="container"]/div[1]/div[@class="row"]/div[1]/h1')->text();
    }

    public function productChipset(): ?string
    {
        if (preg_match('/GeForce (?P<chipset>RTX [0-9]{4}( Ti|-Super)?)/', $this->productName(), $matches)) {
            return $matches['chipset'];
        }
        throw new ChipsetNotFoundException(self::class . " Cannot found chipset for {$this->productPageUrl()}");
    }

    public function productAvailable(): bool
    {
        $result = strtolower($this->crawler->evaluate('//div[@id="js-modal-trigger__availability"]/span[2]')->text());
        return $result === 'en stock';
    }

    public function productPageUrl():string
    {
        return $this->shop->productPageUrl($this->productId);
    }
}

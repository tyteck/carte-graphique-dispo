<?php

namespace App\Shop;

use App\Exceptions\AvailableNotFoundException;
use App\Exceptions\NameNotFoundException;
use App\Exceptions\PriceNotFoundException;
use App\Exceptions\ProductNotFoundException;
use App\Exceptions\UnknownShopException;
use App\Interfaces\Shopable;
use App\Models\Shop;
use App\Traits\Parser;

class LDLC implements Shopable
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
        $this->shop = Shop::bySlug('ldlc');
        if ($this->shop === null) {
            throw new UnknownShopException('This shop ' . __CLASS__ . ' is unknown.');
        }
        $this->productId = $productId;
        $this->crawler = $this->parse($this->productPageUrl());
        $this->check();
    }

    public static function get(string $productId)
    {
        return new static($productId);
    }

    public function check()
    {
        if ($this->crawler->evaluate('//div[@class="main p404"]')->count() > 0) {
            throw new ProductNotFoundException(__CLASS__ . " Product on this page {$this->productPageUrl()} does not exist.");
        }
        return $this;
    }

    public function productPrice() : ?string
    {
        $result = $this->crawler->evaluate('//aside/div[@class="price"]/div');
        if ($result->count()) {
            return str_replace('€', '.', $result->text());
        }
        throw new PriceNotFoundException(__CLASS__ . " Cannot found price for {$this->productPageUrl()}");
    }

    public function productName() : ?string
    {
        $result = $this->crawler->evaluate('//h1[@class="title-1"]');
        if ($result->count()) {
            return  $result->text();
        }
        throw new NameNotFoundException(__CLASS__ . " Cannot found name for {$this->productPageUrl()}");
    }

    public function productAvailable() : bool
    {
        $result = $this->crawler->evaluate('//aside/div[@id="product-page-stock"]/div[@class="website"]/div[@class="content"]/div[1]/span[1]');
        if ($result->count()) {
            $result = strtolower($result->text());
            return $result === 'en stock';
        }
        throw new AvailableNotFoundException(__CLASS__ . " Cannot found availability for {$this->productPageUrl()}");
    }

    public function productPageUrl():string
    {
        return $this->shop->productPageUrl($this->productId);
    }
}

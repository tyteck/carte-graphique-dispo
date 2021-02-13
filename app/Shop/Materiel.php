<?php

namespace App\Shop;

use App\Exceptions\PriceNotFoundException;
use App\Exceptions\ProductNotFoundException;
use App\Exceptions\UnknownShopException;
use App\Interfaces\Shopable;
use App\Models\Shop;
use App\Traits\Parser;

class Materiel implements Shopable
{
    use Parser;

    /** @var \App\Models\Shop $shop */
    protected $shop;

    /** @var string $path */
    protected $path;

    /** @var \Symfony\Component\DomCrawler\Crawler $crawler*/
    protected $crawler ;

    private function __construct(string $path)
    {
        $this->shop = Shop::bySlug('materielnet');
        if ($this->shop === null) {
            throw new UnknownShopException('This shop ' . __CLASS__ . ' is unknown.');
        }
        $this->path = $path;
        $this->crawler = $this->parse($this->productPageUrl());
        $this->check();
    }

    public static function get(string $path)
    {
        return new static($path);
    }

    public function check()
    {
        if ($this->crawler->evaluate('//section[@class="c-site__error"]')->count() > 0) {
            throw new ProductNotFoundException(__CLASS__ . " Product on this page {$this->productPageUrl()} does not exist.");
        }
        return $this;
    }

    public function productPrice() : ?string
    {
        $result = $this->crawler->evaluate('//span[@class="o-product__price"]');
        if ($result->count()) {
            return str_replace('€', '.', $this->crawler->evaluate('//span[@class="o-product__price"]')->text());
        }
        throw new PriceNotFoundException(__CLASS__ . " Cannot found price for {$this->productPageUrl()}");
    }

    public function productName() : ?string
    {
        return $this->crawler->evaluate('//div[@class="container"]/div[1]/div[@class="row"]/div[1]/h1')->text();
    }

    public function productAvailable() : bool
    {
        $result = strtolower($this->crawler->evaluate('//div[@id="js-modal-trigger__availability"]/span[2]')->text());
        return $result === 'en stock';
    }

    public function productPageUrl():string
    {
        return $this->shop->base_url . $this->path;
    }
}

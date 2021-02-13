<?php

namespace App\Shop;

use App\Exceptions\PriceNotFoundException;
use App\Exceptions\ProductNotFoundException;
use App\Exceptions\UnknownShopException;
use App\Interfaces\Shopable;
use App\Models\Shop;
use App\Traits\Parser;

class TopAchat implements Shopable
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
        if (!$this->crawler->evaluate('//body[@class="detail2"]')->count()) {
            throw new ProductNotFoundException(__CLASS__ . " Product on this page {$this->productPageUrl()} does not exist.");
        }
        return $this;
    }

    public function productPrice() : ?string
    {
        $result = $this->crawler->evaluate('//section[@id="panier"]/div[@class="prix"]/div[@class="eproduct NOR"]/span[1]');
        if ($result->count()) {
            if (preg_match('/(?P<price>[0-9.]*)/', $result->text(), $matches)) {
                return($matches['price']);
            }
        }
        throw new PriceNotFoundException(__CLASS__ . " Cannot found price for {$this->productPageUrl()}");
    }

    public function productName() : ?string
    {
        return $this->crawler->evaluate('//div[@class="libelle"]/h1[@class="fn"]')->text();
    }

    public function productAvailable() : bool
    {
        return $this->crawler->evaluate('//section[@id="panier"][@class="cart-box en-stock"]')->count() > 0;
    }

    public function productPageUrl():string
    {
        return $this->shop->base_url . 'pages/detail2_cat_est_micro_puis_rubrique_est_wgfx_pcie_puis_ref_est_in' . $this->productId . '.html';
    }
}

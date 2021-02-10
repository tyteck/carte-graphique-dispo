<?php

namespace App\Shop;

use App\Exceptions\PriceNotFoundException;
use App\Exceptions\ProductNotFoundException;
use App\Interfaces\Shop;
use App\Traits\Parser;

class TopAchat implements Shop
{
    use Parser;

    /** @var string $baseUrl */
    protected $baseUrl = 'https://www.topachat.com/';

    /** @var string $path */
    protected $path;

    /** @var \Symfony\Component\DomCrawler\Crawler $crawler*/
    protected $crawler ;

    private function __construct(string $path)
    {
        $this->path = $path;
        $this->crawler = $this->parse($this->baseUrl . $path);
        $this->check();
    }

    public static function get(string $path)
    {
        return new static($path);
    }

    public function check()
    {
        if ($this->crawler->evaluate('//body[@class="error"]')->count() > 0) {
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
        return $this->baseUrl . $this->path;
    }
}

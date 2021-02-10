<?php

namespace App\Shop;

use App\Interfaces\Shop;
use App\Traits\Parser;

class LDLC implements Shop
{
    use Parser;

    protected $baseUrl = 'https://www.ldlc.com/';

    /** @var \Symfony\Component\DomCrawler\Crawler $crawler*/
    protected $crawler ;

    private function __construct(string $path)
    {
        $this->crawler = $this->parse($this->baseUrl . $path);
    }

    public static function get(string $path)
    {
        return new static($path);
    }

    public function productPrice() : ?string
    {
        return str_replace('€', ',', $this->crawler->evaluate('//aside/div[@class="price"]/div')->text());
    }

    public function productName() : ?string
    {
        return  $this->crawler->evaluate('//h1[@class="title-1"]')->text();
    }

    public function productAvailable() : bool
    {
        $result = strtolower($this->crawler->evaluate('//aside/div[@id="product-page-stock"]/div[@class="website"]/div[@class="content"]/div[1]/span[1]')->text());
        return $result === 'en stock';
    }
}

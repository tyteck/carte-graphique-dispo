<?php

namespace App\Helpers;

use App\Exceptions\OnlineShopUnknownException;
use App\Models\Card;
use App\Models\Shop;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class ExtractDataFromUrl
{
    /** @var \App\Models\Shop $shop */
    protected $shop;

    /** @var \App\Models\Card $card */
    protected $card;

    /** @var string $productUrl */
    protected $productUrl;

    private function __construct(string $productUrl)
    {
        /** check if url is valid */
        if (filter_var($productUrl, FILTER_VALIDATE_URL) === false) {
            $message = "This ({$productUrl}) is not one valid url." ;
            Log::error($message);
            throw new InvalidArgumentException($message);
        }
        $this->productUrl = $productUrl;

        /** parsing url to get domain and product id */
        $urlElements = parse_url($productUrl);
        $this->shop = Shop::byDomain($urlElements['host']);
        if ($this->shop === null) {
            throw new OnlineShopUnknownException("This shop {$urlElements['host']} is unknown yet.");
        }

        /** extracting productId from url */
        $productId = $this->getProductId();

        /** getting card from its url product id */
        $this->card = $this->shop->cardByProductId($productId);
    }

    public static function from(string $productUrl)
    {
        return new static($productUrl);
    }

    public function shop():?Shop
    {
        return $this->shop;
    }

    public function card():?Card
    {
        return $this->card;
    }

    public function getProductId()
    {
        $delimiter = '#';
        $regexp = $delimiter . preg_replace('#' . Shop::PRODUCT_ID_VARIABLE . '#', '(?P<productId>\w+)', $this->shop->product_page_url) . $delimiter;

        if (!preg_match($regexp, $this->productUrl, $matches)) {
            throw new InvalidArgumentException("Regexp ($regexp) not found in url ({$this->productUrl}).");
        }

        return $matches['productId'];
    }
}

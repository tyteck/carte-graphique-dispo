<?php

namespace App\Jobs;

use App\Exceptions\ChipsetUnknownException;
use App\Exceptions\OnlineShopUnknownException;
use App\Factories\CrawlerClassName;
use App\Interfaces\Crawler;
use App\Models\Card;
use App\Models\Chipset;
use App\Models\Shop;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ExtractDataFromUrl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var \App\Interfaces\Crawler $crawler */
    protected $crawler;

    /** @var \App\Models\Shop $shop */
    protected $shop;

    /** @var \App\Models\Card $card */
    protected $card;

    /** @var \App\Models\Chipset $chipset */
    protected $chipset;

    /** @var string $productUrl */
    protected $productUrl;

    /** @var string $productId */
    protected $productId;

    private function __construct(string $productUrl)
    {
        /** check if url is valid */
        if (filter_var($productUrl, FILTER_VALIDATE_URL) === false) {
            $message = "This url ({$productUrl}) is not valid." ;
            Log::error($message);
            throw new InvalidArgumentException($message);
        }
        $this->productUrl = $productUrl;

        /** parsing url to get domain and product id */
        $this->initShop();

        /** extracting productId from url */
        $this->productId = $this->productIdFromProductPageUrl();

        /** getting card from its url product id */
        $this->card = $this->shop->cardByProductId($this->productId);

        if ($this->card === null) {
            /**
             * @var \App\Interfaces\Crawler $crawlerClass
             * getting the class that will crawl url
             */
            $crawlerClass = CrawlerClassName::get($this->shop->slug);

            /** build crawler */
            $this->crawler = $crawlerClass::get($this->productId);

            /** getting chipset */
            $this->initChipset();

            /** create card */
            $this->card = Card::create([
                'name' => $this->crawler->productName(),
                'slug' => Str::slug($this->crawler->productName()),
                'chipset_id' => $this->chipset->id,
                'available' => $this->crawler->productAvailable(),
            ]);
            $this->card->addItInShopWithId($this->shop, $this->productId);
        }
    }

    public static function from(string $productUrl): self
    {
        return new static($productUrl);
    }

    public function shop(): ?Shop
    {
        return $this->shop;
    }

    public function card(): ?Card
    {
        return $this->card;
    }

    public function productIdFromProductPageUrl(): string
    {
        $delimiter = '#';
        /** using product_page_url to create a regexp */
        $regexp = $delimiter . preg_replace('#' . Shop::PRODUCT_ID_VARIABLE . '#', '(?P<productId>\w+)', $this->shop->product_page_url) . $delimiter;

        /** using obtained regexp to obtain productId */
        if (!preg_match($regexp, $this->productUrl, $matches)) {
            throw new InvalidArgumentException("Regexp ($regexp) not found in url ({$this->productUrl}).");
        }

        return $matches['productId'];
    }

    /**
     * get chipset from product page.
     *
     * @throws ChipsetUnknownException;
     */
    public function initChipset()
    {
        $this->chipset = Chipset::bySlug(Str::slug($this->crawler->productChipset()));
        if ($this->chipset === null) {
            throw new ChipsetUnknownException("Chipset {$this->crawler->productChipset()} is unknown yet.");
        }
    }

    /**
     * get shop from product url.
     *
     * @throws ChipsetUnknownException;
     */
    public function initShop()
    {
        /** parsing url to get domain and product id */
        $urlElements = parse_url($this->productUrl);
        $this->shop = Shop::byDomain($urlElements['host']);
        if ($this->shop === null) {
            throw new OnlineShopUnknownException("This shop {$urlElements['host']} is unknown yet.");
        }
    }
}

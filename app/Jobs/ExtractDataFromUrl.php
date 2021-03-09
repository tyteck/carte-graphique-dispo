<?php

namespace App\Helpers;

use App\Crawlers\LDLC;
use App\Crawlers\Materiel;
use App\Crawlers\TopAchat;
use App\Exceptions\OnlineShopUnknownException;
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

    /** @var \App\Models\Shop $shop */
    protected $shop;

    /** @var \App\Models\Card $card */
    protected $card;

    /** @var \App\Models\Chipset $chipset */
    protected $chipset;

    /** @var string $productUrl */
    protected $productUrl;

    /** @var array $shopCrawlersMap */
    protected $shopCrawlersMap = [
        'ldlc' => LDLC::class,
        'materiel' => Materiel::class,
        'top-achat' => TopAchat::class,
    ];

    private function __construct(string $productUrl)
    {
        /** check if url is valid */
        if (filter_var($productUrl, FILTER_VALIDATE_URL) === false) {
            $message = "This url ({$productUrl}) is not one valid." ;
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

        if ($this->card === null) {
            /**
             * @var \App\Interfaces\Shopable $crawlerClass
             * getting the class that will crawl url
             */
            $crawlerClass = $this->shopCrawlersMap[$this->shop->slug];

            /** build crawler */
            $crawler = $crawlerClass::get($productId);

            /** getting chipset */
            $this->chipset = Chipset::bySlug(Str::slug($crawler->productChipset()));

            /** create card */
            $this->card = Card::create([
                'name' => $crawler->productName(),
                'slug' => Str::slug($crawler->productName()),
                'chipset_id' => $this->chipset->id,
                'available' => $crawler->productAvailable(),
            ]);
            $this->card->addItInShopWithId($this->shop, $productId);
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

    public function getProductId(): string
    {
        $delimiter = '#';
        $regexp = $delimiter . preg_replace('#' . Shop::PRODUCT_ID_VARIABLE . '#', '(?P<productId>\w+)', $this->shop->product_page_url) . $delimiter;

        if (!preg_match($regexp, $this->productUrl, $matches)) {
            throw new InvalidArgumentException("Regexp ($regexp) not found in url ({$this->productUrl}).");
        }

        return $matches['productId'];
    }
}

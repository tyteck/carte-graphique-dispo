<?php

namespace App\Factories;

use App\Crawlers\LDLC;
use App\Crawlers\Materiel;
use App\Crawlers\TopAchat;
use App\Exceptions\CrawlerUnknownException;
use App\Interfaces\Crawler;

class CrawlerClassName
{
    /** @var array $shopCrawlersMap */
    protected static $shopCrawlersMap = [
        'ldlc' => LDLC::class,
        'materiel' => Materiel::class,
        'top-achat' => TopAchat::class,
    ];

    public static function get(string $slug): String
    {
        if (!array_key_exists($slug, self::$shopCrawlersMap)) {
            throw new CrawlerUnknownException("This shop slug {$slug} has no crawler at this time.");
        }

        /** build crawler */
        return self::$shopCrawlersMap[$slug];
    }
}

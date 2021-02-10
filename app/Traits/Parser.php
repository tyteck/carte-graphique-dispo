<?php

namespace App\Traits;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler as DomCrawlerCrawler;

trait Parser
{
    public function parse($url):DomCrawlerCrawler
    {
        $client = new Client();

        return $client->request('GET', $url);
    }
}

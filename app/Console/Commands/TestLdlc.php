<?php

namespace App\Console\Commands;

use Goutte\Client;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

class TestLdlc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:ldlc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new Client();

        $crawler = $client->request('GET', 'https://www.ldlc.com/informatique/pieces-informatique/carte-graphique-interne/c4684/+fv1026-5801+fv121-19365.html');

        $items = $crawler->evaluate('//div[@class="listing-product"]/ul[1]/li')->each(function (Crawler $node) {
            dd(get_class($node));
            dump($node->text());
        });

        return 0;
    }
}

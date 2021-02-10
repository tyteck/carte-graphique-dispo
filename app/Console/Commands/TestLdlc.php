<?php

namespace App\Console\Commands;

use Goutte\Client;
use Illuminate\Console\Command;

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

        $crawler = $client->request('GET', 'https://www.ldlc.com/fiche/PB00394604.html');

        //$price = $crawler->evaluate('//div[@class="lister-list"][1]//h3/a');
        $title = $crawler->evaluate('//h1[@class="title-1"]')->text();
        $price = $crawler->evaluate('//aside/div[@class="price"]/div')->text();
        $available = $crawler->evaluate('//aside/div[@id="product-page-stock"]/div[@class="website"]/div[@class="content"]/div[1]/span[1]')->text();

        dd($title, $price, $available);
        return 0;
    }
}

<?php

namespace App\Console\Commands;

use App\Mail\CardAvailable;
use App\Models\Card;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class UpdateCard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:card';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /** @var array $shopMap */
    protected $shopMap = [
        'ldlc' => \App\Shop\LDLC::class,
        'materielnet' => \App\Shop\Materiel::class,
        'top-achat' => \App\Shop\TopAchat::class,
    ];

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
        /** for this cgu */
        $card = Card::bySlug('msi-geforce-rtx-3060-ti-ventus-2x-oc');
        $shops = $card->shops()->get();
        /** in all shops */
        foreach ($shops as $shop) {
            /** with slug which crawler to be used */
            $class = $this->shopMap[$shop->slug];

            /** @var \App\Interfaces\Shopable $shopCrawler  */
            $shopCrawler = $class::get($shop->pivot->product_id);

            if ($shopCrawler->productAvailable() == true) {
                
                
            }
        }

        return 0;
    }
}

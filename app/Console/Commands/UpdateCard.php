<?php

namespace App\Console\Commands;

use App\Models\Card;
use App\Shop\LDLC;
use App\Shop\Materiel;
use App\Shop\TopAchat;
use Illuminate\Console\Command;

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
    protected $shopMap;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->shopMap = [
            'ldlc' => LDLC::class,
            'materielnet' => Materiel::class,
            'top-achat' => TopAchat::class,
        ];
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
        $inShops = Card::bySlug('msi-geforce-rtx-3060-ti-ventus-2x-oc')->inShops;

        foreach ($inShops as $productInShop) {
            //dump($productInShop->shop);
            $foo = new $this->shopMap[];
            /** get availability */
            //LDLC::get($ca)->productAvailable();
        }

        /* Materiel::get(self::AVAILABLE_PRODUCT)->productAvailable();
        TopAchat::get(self::AVAILABLE_PRODUCT)->productAvailable(); */

        return 0;
    }
}
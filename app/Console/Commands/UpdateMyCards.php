<?php

namespace App\Console\Commands;

use App\Models\Card;
use App\Sms\SendSms;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateMyCard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:mycards';

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
        $slugs = [
            'msi-geforce-rtx-3060-ti-ventus-2x-oc',
            'msi-geforce-rtx-3060-ti-gaming-x-trio',
        ];
        $card = Card::bySlug('msi-geforce-rtx-3060-ti-ventus-2x-oc');
        $shops = $card->shops()->get();
        if (!$shops->count()) {
            throw new Exception('No shop registered ? Really !!');
        }
        /** in all shops */
        foreach ($shops as $shop) {
            /** with slug which crawler to be used */
            $class = $this->shopMap[$shop->slug];

            /** @var \App\Interfaces\Shopable $shopCrawler  */
            $shopCrawler = $class::get($shop->pivot->product_id);

            if ($shopCrawler->productAvailable() == true) {
                $smsBody = "Ta carte graphique est dispo ici {$card->productUrlForShop($shop)} maintenant !";
                $result = SendSms::init()
                    ->toRecipient(env('SMS_TO'))
                    ->send($smsBody);
                if ($result === false) {
                    $message = 'Sending sms has failed';
                    $this->error($message, 'v');
                    Log::error($message);
                    return 1;
                }
                $message = 'Sms sent successfully to ' . env('SMS_TO');
                $this->info($message, 'v');
                Log::notice($message);
            }
        }
        Log::notice("{$this->signature} has finished.");
        return 0;
    }
}

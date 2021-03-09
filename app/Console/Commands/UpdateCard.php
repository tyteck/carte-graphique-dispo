<?php

namespace App\Console\Commands;

use App\Models\Card;
use App\Sms\SendSms;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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

    /** @var array $shopSlugMap */
    protected $shopSlugMap = [
        'ldlc' => \App\Crawlers\LDLC::class,
        'materielnet' => \App\Crawlers\Materiel::class,
        'top-achat' => \App\Crawlers\TopAchat::class,
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
        if (!$shops->count()) {
            throw new Exception('No shop registered ? Really !!');
        }
        /** in all shops */
        foreach ($shops as $shop) {
            /** with slug which crawler to be used */
            $class = $this->shopSlugMap[$shop->slug];

            /** @var \App\Interfaces\Crawler $shopCrawler  */
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
        $message = "{$this->signature} has finished successfully.";
        Log::notice($message);
        $this->info($message);
        return 0;
    }
}

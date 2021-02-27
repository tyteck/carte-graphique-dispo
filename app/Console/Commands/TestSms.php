<?php

namespace App\Console\Commands;

use App\Models\Card;
use App\Sms\SendSms;
use Illuminate\Console\Command;

class TestSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send myself one test sms';

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
        $card = Card::bySlug('msi-geforce-rtx-3060-ti-ventus-2x-oc');
        $shop = $card->shops()->first();
        $smsBody = "Ta carte graphique est dispo ici {$card->productUrlForShop($shop)} maintenant !";
        $result = SendSms::init()
            ->toRecipient(env('SMS_TO'))
            ->send($smsBody);
        if ($result === false) {
            $this->error('Sending sms has failed');
            return 1;
        }
        $this->info('Sms sent successfully to ' . env('SMS_TO'));
        return 0;
    }
}

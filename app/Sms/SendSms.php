<?php

namespace App\Sms;

use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;

class SendSms
{
    /** @var \Vonage\Client\Credentials\Basic $basic*/
    protected $basic;

    /** @var \Vonage\Client $client*/
    protected $client;

    /** @var string $brandName */
    protected $brandName;

    /** @var string $recipient */
    protected $recipient;

    private function __construct()
    {
        $this->basic = new Basic(
            config('services.vonage.api_key'),
            config('services.vonage.api_secret')
        );
        $this->client = new Client($this->basic);
        $this->brandName = config('app.name');
    }

    public static function init(...$params)
    {
        return new static(...$params);
    }

    public function toRecipient(string $phoneNumber)
    {
        $this->recipient = $phoneNumber;
        return $this;
    }

    public function send(string $smsText)
    {
        $sms = new SMS($this->recipient, $this->brandName, $smsText);
        $response = $this->client->sms()->send($sms);
        $message = $response->current();
        return $message->getStatus() === 0;
    }
}

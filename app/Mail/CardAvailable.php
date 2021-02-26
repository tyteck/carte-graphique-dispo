<?php

namespace App\Mail;

use App\Models\Card;
use App\Models\Shop;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CardAvailable extends Mailable
{
    use Queueable, SerializesModels;

    /** @var \App\Models\Card $card */
    public $card;

    /** @var \App\Models\Shop $shop */
    public $shop;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Card $card, Shop $shop)
    {
        $this->card = $card;
        $this->shop = $shop;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.card_available');
    }
}

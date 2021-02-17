<?php

namespace App\Mail;

use App\Models\ProductInShop;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CardAvailable extends Mailable
{
    use Queueable, SerializesModels;

    /** @var \App\Models\ProductInShop */
    public $productInShop;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ProductInShop $productInShop)
    {
        $this->productInShop = $productInShop;
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

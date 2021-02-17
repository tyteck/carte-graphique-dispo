<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInShop extends Model
{
    use HasFactory;

    /** @var array $shopMap */
    protected $shopMap = [
        'ldlc' => \App\Shop\LDLC::class,
        'materielnet' => \App\Shop\Materiel::class,
        'top-achat' => \App\Shop\TopAchat::class,
    ];

    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function card()
    {
        return $this->belongsTo(Shop::class);
    }

    public function getCrawler()
    {
        1;
    }
}

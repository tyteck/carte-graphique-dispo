<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public static function bySlug(string $slug): ?Shop
    {
        return self::where('slug', $slug)->first();
    }

    public function cards()
    {
        return $this->belongsToMany(Card::class);
    }

    public function addCardInShop(Card $card, string $productId)
    {
        $this->cards()->attach($card, [
            'product_id' => $productId,
        ]);
    }

    public function productPageUrl(string $productId)
    {
        return preg_replace('#{{PRODUCT_ID}}#', $productId, $this->base_url);
    }
}

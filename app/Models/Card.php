<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'available' => 'boolean',
    ];

    public function chipset()
    {
        return $this->belongsTo(Chipset::class);
    }

    public function shops()
    {
        return $this->belongsToMany(Shop::class)->withPivot('product_id');
    }

    public static function bySlug(string $slug): ?Card
    {
        return self::where('slug', $slug)->first();
    }

    public function addItInShopWithId(Shop $shop, string $productId)
    {
        $this->shops()->attach($shop, ['product_id' => $productId]);
    }

    public function isCardInShop(Shop $shop)
    {
        return $this->whereHas('shops', function ($query) use ($shop) {
            return $query->where('shops.id', '=', $shop->id);
        })->count() > 0;
    }

    public function productIdInShop(Shop $shop)
    {
        $result = $this->shops->filter(function ($item) use ($shop) {
            return $item->id === $shop->id;
        });
        if (!$result->count()) {
            return null;
        }

        return $result->first()->pivot->product_id;
    }

    public function productUrlForShop(Shop $shop)
    {
        $productId = $this->productIdInShop($shop);

        if ($productId === null) {
            return null;
        }

        return $shop->productPageUrl($productId);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use LogicException;

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
        if (!strlen($productId)) {
            throw new InvalidArgumentException('product_id is empty.');
        }
        $result = preg_replace('#{{PRODUCT_ID}}#', $productId, $this->product_page_url, 1, $found);
        if (!$found > 0) {
            throw new LogicException("Column product_page_url for shop {{$this->name}} is invalid.");
        }
        return $result;
    }
}

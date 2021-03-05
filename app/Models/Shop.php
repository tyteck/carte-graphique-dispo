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

    /** this is the url part I replace with the real product id */
    public const PRODUCT_ID_VARIABLE = '{{PRODUCT_ID}}';

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
        return $this->belongsToMany(Card::class)->withPivot('product_id');
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
        $result = preg_replace('#' . self::PRODUCT_ID_VARIABLE . '#', $productId, $this->product_page_url, 1, $found);
        if (!$found > 0) {
            throw new LogicException("Column product_page_url for shop {{$this->name}} is invalid.");
        }
        return "https://{$this->domain_name}/$result";
    }

    public static function byDomain(string $domain): ?Shop
    {
        return self::where('domain_name', $domain)->first();
    }

    public function cardByProductId(string $productId):?Card
    {
        return $this->cards()->wherePivot('product_id', '=', $productId)->first();
    }

    public function cardWithProductIdExists(string $productId):bool
    {
        return $this->cards()->wherePivot('product_id', '=', $productId)->count();
    }
}

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

    public function inShopProducts()
    {
        return $this->hasMany(InShopProduct::class);
    }
}

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

    public function InShopProduct()
    {
        return $this->hasMany(InShopProduct::class);
    }

    public static function bySlug(string $slug): ?Card
    {
        return self::where('slug', $slug)->first();
    }
}

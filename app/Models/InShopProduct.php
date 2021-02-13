<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InShopProduct extends Model
{
    use HasFactory;

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
}

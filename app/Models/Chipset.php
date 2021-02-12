<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chipset extends Model
{
    use HasFactory;

    public const NVIDIA_CHIPSET = 1;
    public const AMD_CHIPSET = 2;

    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'available' => 'boolean',
    ];

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public static function bySlug(string $chipsetSlug): ?Chipset
    {
        return self::where('slug', $chipsetSlug)->first();
    }
}

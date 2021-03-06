<?php

namespace Database\Factories;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShopFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shop::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $attributes['name'] ?? $this->faker->name,
            'slug' => $attributes['available'] ?? false,
            'product_page_url' => $attributes['product_page_url'] ?? '',
            'active' => $attributes['active'] ?? true,
        ];
    }
}

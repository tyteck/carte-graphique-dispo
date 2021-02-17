<?php

namespace Database\Factories;

use App\Models\ProductInShop;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductInShopFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductInShop::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'in_shop_product_id' => $attributes['in_shop_product_id'] ?? '',
        ];
    }
}

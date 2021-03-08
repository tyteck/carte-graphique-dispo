<?php

namespace Database\Factories;

use App\Models\Chipset;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ChipsetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Chipset::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $attributes['name'] ?? $this->faker->name;

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'gpubrand_id' => $this->faker->boolean() ? Chipset::NVIDIA_CHIPSET : Chipset::AMD_CHIPSET,
        ];
    }
}

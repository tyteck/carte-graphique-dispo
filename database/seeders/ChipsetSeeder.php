<?php

namespace Database\Seeders;

use App\Models\Chipset;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ChipsetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'RTX 3090', 'gpubrand_id' => Chipset::NVIDIA_CHIPSET],
            ['name' => 'RTX 3080', 'gpubrand_id' => Chipset::NVIDIA_CHIPSET],
            ['name' => 'RTX 3070', 'gpubrand_id' => Chipset::NVIDIA_CHIPSET],
            ['name' => 'RTX 3060 Ti', 'gpubrand_id' => Chipset::NVIDIA_CHIPSET],
            ['name' => 'RTX 3060', 'gpubrand_id' => Chipset::NVIDIA_CHIPSET],
            ['name' => 'RTX 2080 Super', 'gpubrand_id' => Chipset::NVIDIA_CHIPSET],
            ['name' => 'RTX 2080', 'gpubrand_id' => Chipset::NVIDIA_CHIPSET],
            ['name' => 'RTX 2070 Super', 'gpubrand_id' => Chipset::NVIDIA_CHIPSET],
        ];

        $index = 1;
        $data = array_map(
            function ($item) use (&$index) {
                return array_merge($item, [
                    'id' => $index++,
                    'slug' => Str::slug($item['name']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            },
            $data
        );
        Chipset::insert($data);
    }
}

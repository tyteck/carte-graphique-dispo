<?php

namespace Database\Seeders;

use App\Models\Chipset;
use Illuminate\Database\Seeder;

class ChipsetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Chipset::factory()->create(['name' => 'RTX 3090', 'slug' => 'rtx_3090', 'gpubrand_id' => Chipset::NVIDIA_CHIPSET]);
        Chipset::factory()->create(['name' => 'RTX 3080', 'slug' => 'rtx_3080', 'gpubrand_id' => Chipset::NVIDIA_CHIPSET]);
        Chipset::factory()->create(['name' => 'RTX 3070', 'slug' => 'rtx_3070', 'gpubrand_id' => Chipset::NVIDIA_CHIPSET]);
        Chipset::factory()->create(['name' => 'RTX 3060 Ti', 'slug' => 'rtx_3060_ti', 'gpubrand_id' => Chipset::NVIDIA_CHIPSET]);
        Chipset::factory()->create(['name' => 'RTX 3060', 'slug' => 'rtx_3060', 'gpubrand_id' => Chipset::NVIDIA_CHIPSET]);
        Chipset::factory()->create(['name' => 'RTX 2080 Super', 'slug' => 'rtx_2080_super', 'gpubrand_id' => Chipset::NVIDIA_CHIPSET]);
        Chipset::factory()->create(['name' => 'RTX 2080', 'slug' => 'rtx_2080', 'gpubrand_id' => Chipset::NVIDIA_CHIPSET]);
        Chipset::factory()->create(['name' => 'RTX 2070 Super', 'slug' => 'rtx_2070_super', 'gpubrand_id' => Chipset::NVIDIA_CHIPSET]);
    }
}

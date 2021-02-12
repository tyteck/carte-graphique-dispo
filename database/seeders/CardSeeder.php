<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Chipset;
use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rtx3060ti = Chipset::bySlug('rtx_3060_ti')->id;
        Card::factory()->create(['name' => 'ZOTAC GAMING GEFORCE RTX 3060 Ti Twin Edge', 'chipset_id' => $rtx3060ti, 'available' => false]);
        Card::factory()->create(['name' => 'MSI GeForce RTX 3060 Ti Ventus 2X OC', 'chipset_id' => $rtx3060ti, 'available' => false]);
        Card::factory()->create(['name' => 'PALIT GeForce RTX 3060 Ti DUAL', 'chipset_id' => $rtx3060ti, 'available' => false]);
        Card::factory()->create(['name' => 'PALIT GeForce RTX 3060 Ti DUAL OC', 'chipset_id' => $rtx3060ti, 'available' => false]);
        Card::factory()->create(['name' => 'ZOTAC GAMING GEFORCE RTX 3060 Ti Twin Edge OC', 'chipset_id' => $rtx3060ti, 'available' => false]);
        Card::factory()->create(['name' => 'PALIT GeForce RTX 3060 Ti GAMING PRO', 'chipset_id' => $rtx3060ti, 'available' => false]);
        Card::factory()->create(['name' => 'PALIT GeForce RTX 3060 Ti GAMING PRO OC', 'chipset_id' => $rtx3060ti, 'available' => false]);
        Card::factory()->create(['name' => 'MSI GeForce RTX 3060 Ti Gaming X Trio', 'chipset_id' => $rtx3060ti, 'available' => false]);
        Card::factory()->create(['name' => 'PNY GeForce RTX 3060 Ti 8GB UPRISING Dual Fan', 'chipset_id' => $rtx3060ti, 'available' => false]);
        Card::factory()->create(['name' => 'EVGA GeForce RTX 3060 Ti FTW3 ULTRA', 'chipset_id' => $rtx3060ti, 'available' => false]);
    }
}

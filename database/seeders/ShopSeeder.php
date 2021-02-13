<?php

namespace Database\Seeders;

use App\Models\Shop;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'LDLC', 'base_url' => 'https://www.ldlc.com/'],
            ['name' => 'Materiel.net', 'base_url' => 'https://www.materiel.net/'],
            ['name' => 'Top Achat', 'base_url' => 'https://www.topachat.com/'],
            ['name' => 'Gros Bill', 'base_url' => 'https://www.grosbill.com/'],
            ['name' => 'Fnac', 'base_url' => 'https://www.fnac.com/'],
            ['name' => 'Darty', 'base_url' => 'https://www.darty.com/'],
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
        Shop::insert($data);
    }
}

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
            ['name' => 'LDLC', 'product_page_url' => 'https://www.ldlc.com/fiche/{{PRODUCT_ID}}.html', 'active' => 1],
            ['name' => 'Materiel.net', 'product_page_url' => 'https://www.materiel.net/produit/{{PRODUCT_ID}}.html', 'active' => 1],
            ['name' => 'Top Achat', 'product_page_url' => 'https://www.topachat.com/pages/detail2_cat_est_micro_puis_rubrique_est_wgfx_pcie_puis_ref_est_in{{PRODUCT_ID}}.html', 'active' => 1],
            ['name' => 'Gros Bill', 'product_page_url' => 'https://www.grosbill.com/', 'active' => 0],
            ['name' => 'Fnac', 'product_page_url' => 'https://www.fnac.com/Carte-graphique-Gaming/{{PRODUCT_ID}}/', 'active' => 0],
            ['name' => 'Darty', 'product_page_url' => 'https://www.darty.com/', 'active' => 0],
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

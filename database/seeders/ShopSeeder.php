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
            ['name' => 'LDLC', 'domain_name' => 'www.ldlc.com', 'product_page_url' => 'fiche/{{PRODUCT_ID}}.html', 'active' => 1],
            ['name' => 'Materiel.net', 'domain_name' => 'www.materiel.net', 'product_page_url' => 'produit/{{PRODUCT_ID}}.html', 'active' => 1],
            ['name' => 'Top Achat', 'domain_name' => 'www.topachat.com', 'product_page_url' => 'pages/detail2_cat_est_micro_puis_rubrique_est_wgfx_pcie_puis_ref_est_in{{PRODUCT_ID}}.html', 'active' => 1],
            ['name' => 'Gros Bill', 'domain_name' => 'www.grosbill.com', 'product_page_url' => '', 'active' => 0],
            ['name' => 'Fnac', 'domain_name' => 'www.fnac.com', 'product_page_url' => 'Carte-graphique-Gaming/{{PRODUCT_ID}}/', 'active' => 0],
            ['name' => 'Darty', 'domain_name' => 'www.darty.com', 'product_page_url' => '', 'active' => 0],
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

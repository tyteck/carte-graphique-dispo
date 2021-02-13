<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\InShopProduct;
use App\Models\Shop;
use Illuminate\Database\Seeder;

class InShopProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $index = 1;

        /** getting shops id i will use */
        $ldlcId = Shop::bySlug('ldlc')->id;
        $materielId = Shop::bySlug('materielnet')->id;
        $topAchatId = Shop::bySlug('top-achat')->id;

        /** card by card */
        $card = Card::bySlug('zotac-gaming-geforce-rtx-3060-ti-twin-edge');
        $data = [
            ['shop_id' => $ldlcId, 'card_id' => $card->id, 'in_shop_product_id' => 'PB00394053', ],
            ['shop_id' => $materielId, 'card_id' => $card->id, 'in_shop_product_id' => '202011060034', ],
            ['shop_id' => $topAchatId, 'card_id' => $card->id, 'in_shop_product_id' => '20006277', ],
        ];

        $data = array_map(
            function ($item) use (&$index) {
                return array_merge($item, [
                    'id' => $index++,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            },
            $data
        );
        InShopProduct::insert($data);
    }
}

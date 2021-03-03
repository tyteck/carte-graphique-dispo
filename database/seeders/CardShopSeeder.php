<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Shop;
use Illuminate\Database\Seeder;

class CardShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** getting shops id i will use */
        $ldlc = Shop::bySlug('ldlc');
        $materiel = Shop::bySlug('materielnet');
        $topAchat = Shop::bySlug('top-achat');

        /** card by card */
        $card = Card::bySlug('zotac-gaming-geforce-rtx-3060-ti-twin-edge');
        $card->addItInShopWithId($ldlc, 'PB00394053');
        $card->addItInShopWithId($materiel, '202011060034');
        $card->addItInShopWithId($topAchat, '20006277');

        /** the one I want */
        $card = Card::bySlug('msi-geforce-rtx-3060-ti-ventus-2x-oc');
        $card->addItInShopWithId($ldlc, 'PB00395231');
        $card->addItInShopWithId($materiel, '202012020119');
        $card->addItInShopWithId($topAchat, '20006745');

        $card = Card::bySlug('msi-geforce-rtx-3060-ti-gaming-x-trio');
        $card->addItInShopWithId($ldlc, 'PB00394151');
        $card->addItInShopWithId($materiel, '202011200137');
        $card->addItInShopWithId($topAchat, '20006537');
    }
}

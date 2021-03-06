<?php

namespace Tests;

use App\Models\Card;
use App\Models\Chipset;
use App\Models\Shop;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function createLdlc()
    {
        return Shop::factory()->create(
            [
                'domain_name' => 'www.ldlc.com',
                'product_page_url' => 'fiche/{{PRODUCT_ID}}.html',
                'slug' => 'ldlc'
            ]
        );
    }

    public function createCardWithSlug(string $slug = 'test'):Card
    {
        return Card::factory()
            ->for(Chipset::factory())
            ->create(['slug' => $slug]);
    }
}

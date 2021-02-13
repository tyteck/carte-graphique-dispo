<?php

namespace Tests\Unit;

use App\Models\Card;
use App\Models\Chipset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CardModelTest extends TestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
    }

    public function testingBySlug()
    {
        $this->assertNull(Card::bySlug('do not exist'));

        $Card = Card::factory()
            ->for(Chipset::factory())
            ->create(['slug' => 'test']);
        $this->assertNotNull(Card::bySlug('test'));
        $this->assertInstanceOf(Card::class, Card::bySlug('test'));
        $this->assertEquals($Card->id, Card::bySlug('test')->id);
    }
}

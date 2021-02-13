<?php

namespace Tests\Unit;

use App\Models\Chipset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChipsetModelTest extends TestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
    }

    public function testingBySlug()
    {
        $this->assertNull(Chipset::bySlug('do not exist'));

        $chipset = Chipset::factory()->create(['slug' => 'test']);
        $this->assertNotNull(Chipset::bySlug('test'));
        $this->assertInstanceOf(Chipset::class, Chipset::bySlug('test'));
        $this->assertEquals($chipset->id, Chipset::bySlug('test')->id);
    }
}

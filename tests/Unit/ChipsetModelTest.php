<?php

namespace Tests\Unit;

use App\Models\Chipset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChipsetModelTest extends TestCase
{
    use RefreshDatabase;

    public const ASUS_GEFORCE_ROG_STRIX_RTX_3060_TI = 'fiche/PB00394604.html';
    public const AVAILABLE_PRODUCT = 'fiche/PB00387288.html';

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

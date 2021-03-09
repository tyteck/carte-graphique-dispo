<?php

namespace Tests\Unit;

use App\Crawlers\LDLC;
use App\Crawlers\Materiel;
use App\Crawlers\TopAchat;
use App\Exceptions\CrawlerUnknownException;
use App\Factories\CrawlerClassName;
use Tests\TestCase;

class CrawlerClassNameTest extends TestCase
{
    /** @test */
    public function get_should_throw_exception()
    {
        $this->expectException(CrawlerUnknownException::class);
        CrawlerClassName::get('this-is-not-a-shop');
    }

    /** @test */
    public function get_is_ok()
    {
        $this->assertEquals(LDLC::class, CrawlerClassName::get('ldlc'));
        $this->assertEquals(Materiel::class, CrawlerClassName::get('materiel'));
        $this->assertEquals(TopAchat::class, CrawlerClassName::get('top-achat'));
    }
}

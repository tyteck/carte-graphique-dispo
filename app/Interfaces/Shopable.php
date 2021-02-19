<?php

namespace App\Interfaces;

interface Shopable
{
    public function productPrice() : ?string;

    public function productName() : ?string;

    public function productAvailable() : bool;
}

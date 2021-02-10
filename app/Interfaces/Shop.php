<?php

namespace App\Interfaces;

interface Shop
{
    public function productPrice() : ?string;

    public function productName() : ?string;

    public function productAvailable() : bool;
}

<?php

namespace App\Interfaces;

interface Shopable
{
    public static function get(string $productId): self;

    public function productPrice(): ?string;

    public function productName(): ?string;

    public function productChipset(): ?string;

    public function productAvailable(): bool;

    public function productPageUrl(): string;
}

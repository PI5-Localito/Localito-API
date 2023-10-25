<?php

namespace App\Entity;

use App\Storage\StorageResultParseableTrait;

class Stand extends Base
{
    use StorageResultParseableTrait;

    protected int $stand_id;
    protected int $id_seller;
    protected string $tag;
    protected string $stand_name;
    protected ?string $info = null;
    protected int $city;

    public function setStand(Stand $stand): static
    {
        $this->stand_id = $stand->id();
        return $this;
    }

    public function getStand(bool $raw = false): int|Stand
    {
        return $raw ? $this->stand_id : new Stand($this->stand_id);
    }

    public function setSeller(Seller $seller): static
    {
        $this->id_seller = $seller->id();
        return $this;
    }

    public function getSeller(bool $raw = false): int|Seller
    {
        return $raw ? $this->id_seller : new Seller($this->id_seller);
    }

    public function setTag(string $tag): static
    {
        $this->tag = mb_strcut($tag, 0, 25);
        return $this;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function setName(string $stand_name): static
    {
        $this->stand_name = mb_strcut($stand_name, 0, 127);
        return $this;
    }

    public function getName(): string
    {
        return $this->stand_name;
    }

    public function setInfo(string $info): static
    {
        $this->info = $info;
        return $this;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setCity(City $city): static
    {
        $this->city = $city->id();
        return $this;
    }

    public function getCity(bool $raw = false): int|City
    {
        return $raw ? $this->city : new City($this->city);
    }
}


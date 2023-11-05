<?php

namespace App\Entity;

use Lib\Storage\Entity;
use Lib\Storage\Traits\MethodHydrator;

class Product implements Entity
{
    use MethodHydrator;

    protected ?int $id;
    protected int $standId;
    protected string $name;
    protected string $info;
    protected string $image;
    protected float $price;
    
    /**
     * @return array<string,array>
     */
    public function mappings(): array{
        return [
            'id' => [$this->getId, $this->setId],
            'stand_id' => [$this->getStand, $this->setStand],
            'name' => [$this->getName, $this->setName],
            'info' => [$this->getInfo, $this->setInfo],
            'image' => [$this->getImage, $this->setImage],
            'price' => [$this->getPrice, $this->setPrice],
        ];
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setStand(int $sid): static
    {
        $this->standId = $sid;
        return $this;
    }

    public function getStand(): string
    {
        return $this->standId;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setInfo(string $info): static
    {
        $this->info = $info;
        return $this;
    }

    public function getInfo(): string
    {
        return $this->info;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}


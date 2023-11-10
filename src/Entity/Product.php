<?php

namespace App\Entity;

use App\Model\ProductRepo;
use Lib\Storage\AbstractEntity;
use Lib\Storage\Annotations\Column;
use Lib\Storage\Annotations\Table;
use Lib\Storage\Traits\AnnotationMappings;
use Lib\Storage\Traits\ColumnHydrate;
use Symfony\Component\Validator\Constraints as Assert;

#[Table('products', ProductRepo::class)]
class Product extends AbstractEntity
{
    use AnnotationMappings;
    use ColumnHydrate;

    #[Column('stand_id')]
    public int $standId;

    #[Column('name')]
    public string $name;

    #[Column('info')]
    public string $info;

    #[Column('image')]
    public string $image;

    #[Column('price')]
    public float $price;

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

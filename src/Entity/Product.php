<?php

namespace App\Entity;

use App\Model\ProductRepo;
use Lib\Storage\AbstractEntity;
use Lib\Storage\Annotations\Column;
use Lib\Storage\Annotations\Table;
use Lib\Storage\Traits\AnnotationMappings;
use Lib\Storage\Traits\ColumnHydrate;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

#[Table('products', ProductRepo::class)]
class Product extends AbstractEntity
{
    use AnnotationMappings;
    use ColumnHydrate;

    #[Column('stand_id')]
    #[Assert\NotBlank(message: 'not.blank')]
    #[Assert\NotNull(message: 'not.null')]
    public int $standId;

    #[Column('name')]
    #[Assert\NotBlank(message: 'not.blank')]
    #[Assert\NotNull(message: 'not.null')]
    public string $name;

    #[Column('info')]
    public string $info;

    #[Column('image', 'getImageUri', 'setImageFromUri')]
    public ?File $image = null;

    #[Column('price')]
    #[Assert\NotBlank(message: 'not.blank')]
    #[Assert\NotNull(message: 'not.null')]
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
        $this->name = mb_strcut($name, 0, 127);
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setInfo(string $info): static
    {
        $this->info = mb_strcut($info, 0, 255);
        return $this;
    }

    public function getInfo(): string
    {
        return $this->info;
    }

    public function getImageUri(): ?string
    {
        return $this->image?->getPathname();
    }

    public function setImageFromUri(?string $path): static
    {
        if (!empty($path)) {
            $this->image = new File($path, false);
        }
        return $this;
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

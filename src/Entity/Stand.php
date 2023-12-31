<?php

namespace App\Entity;

use App\Model\StandRepo;
use Lib\Storage\AbstractEntity;
use Lib\Storage\Annotations\Column;
use Lib\Storage\Annotations\Table;
use Lib\Storage\Traits\AnnotationMappings;
use Lib\Storage\Traits\ColumnHydrate;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

#[Table('stands', StandRepo::class)]
class Stand extends AbstractEntity
{
    use ColumnHydrate;
    use AnnotationMappings;

    #[Column('seller_id')]
    #[Assert\NotBlank(message: 'not.blank')]
    #[Assert\NotNull(message: 'not.null')]
    public int $sellerId;

    #[Column('tag')]
    #[Assert\NotBlank(message: 'not.blank')]
    #[Assert\NotNull(message: 'not.null')]
    public string $tag;

    #[Column('stand_name')]
    #[Assert\NotBlank(message: 'not.blank')]
    #[Assert\NotNull(message: 'not.null')]
    public string $name;

    #[Column('info')]
    public ?string $info;

    #[Column('city')]
    #[Assert\NotBlank(message: 'not.blank')]
    #[Assert\NotNull(message: 'not.null')]
    public int $city;

    #[Column('category')]
    public string $category;

    #[Column('image', 'getImageUri', 'setImageFromUri')]
    public ?File $image = null;

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setSeller(int $sid): static
    {
        $this->sellerId = $sid;
        return $this;
    }

    public function getSeller(): int
    {
        return $this->sellerId;
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
        $this->info = $info;
        return $this;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setCity(int $cid): static
    {
        $this->city = $cid;
        return $this;
    }

    public function getCity(): int
    {
        return $this->city;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;
        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
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
}

<?php

namespace App\Entity;

use Lib\Storage\Entity;
use Lib\Storage\Traits\MethodHydrator;

class Stand implements Entity
{
    use MethodHydrator;

    protected ?int $id;
    protected int $idSeller;
    protected string $tag;
    protected string $standName;
    protected ?string $info;
    protected int $city;

    /**
     * @return array<string,array>
     */
    public function mappings(): array{
        return [
            'id' => [$this->getId, $this->setId],
            'seller_id' => [$this->getSeller, $this->setSeller],
            'tag' => [$this->getTag, $this->setTag],
            'stand_name' => [$this->getName, $this->setName],
            'info' => [$this->getInfo, $this->setInfo],
            'city' => [$this->getCity, $this->setCity],
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

    public function setSeller(int $sid): static
    {
        $this->idSeller = $sid;
        return $this;
    }

    public function getSeller(): int
    {
        return $this->idSeller;
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

    public function setName(string $standName): static
    {
        $this->standName = mb_strcut($standName, 0, 127);
        return $this;
    }

    public function getName(): string
    {
        return $this->standName;
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
}


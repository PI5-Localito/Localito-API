<?php

namespace App\Entity;

use Lib\Storage\Entity;
use Lib\Storage\Traits\MethodHydrator;


class City implements Entity
{
    use MethodHydrator;

    protected ?int $id;
    protected string $countryCode;
    protected string $cityName;
    protected float $longitude;
    protected float $latitude;
    /**
     * @return array<string,array>
     */
    public function  mappings(): array
    {
        return [
            'id' => [$this->getId, $this->setId],
            'country_code' => [$this->getCode, $this->setCode],
            'city_name' => [$this->getName, $this->setName],
            'longitude' => [$this->getLong, $this->setLong],
            'latitude' => [$this->getLat, $this->setLat]
        ];

    }


    public function setId(?int $id = null): static
    {
        $this->id = $id;
        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setCountry(string $countryCode): static
    {
        $this->countryCode = mb_strcut($countryCode, 0, 3);
        return $this;
    }

    public function getCountry(): string
    {
        return $this->countryCode;
    }

    public function setName(string $cityName): static
    {
        $this->cityName = mb_strcut($cityName, 0, 255);
        return $this;
    }

    public function getName(): string
    {
        return $this->city_name;
    }

    public function setLong(float $longitude): static
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getLong(): float
    {
        return $this->longitude;
    }

    public function setLat(float $latitude): static
    {
        $this->latitude = $latitude;
        return $this;
    }
}


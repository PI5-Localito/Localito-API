<?php

namespace App\Entity;

use App\Model\CityRepo;
use Lib\Storage\AbstractEntity;
use Lib\Storage\Annotations\Column;
use Lib\Storage\Annotations\Table;
use Lib\Storage\Traits\AnnotationMappings;
use Lib\Storage\Traits\ColumnHydrate;
use Symfony\Component\Validator\Constraints as Assert;

#[Table('cities', CityRepo::class)]
class City extends AbstractEntity
{
    use AnnotationMappings;
    use ColumnHydrate;

    #[Column('country_code')]
    #[Assert\Length(exactly: 3, exactMessage: 'string.length')]
    public string $countryCode;

    #[Column('city_name')]
    public string $cityName;

    #[Column('longitude')]
    public float $longitude;

    #[Column('latitude')]
    public float $latitude;

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): int
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
        return $this->cityName;
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

    public function getLat(): float
    {
        return $this->latitude;
    }
}

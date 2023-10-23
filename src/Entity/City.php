<?php

namespace App\Entity;

use App\Storage\StorageResultParseableTrait;

class City extends Base{
	use StorageResultParseableTrait;

	protected int $id;
	protected string $country_code;
	protected string $city_name;
	protected float $longitude;
	protected float $latitude;

	public function setCity(int $id): static{
		$this->id = $id;
		return $this;
	}

	public function getCity():int{
		return $this->id;
	} 

	public function setCountry(string $country_code): static{
		$this->country_code = mb_strcut($country_code, 0, 3);
		return $this;
	}

	public function getCountry(): string{
		return $this->country_code;
	}

	public function setName(string $city_name): static{
		$this->city_name = mb_strcut($city_name, 0, 255);
		return $this;
	}

	public function getName(): string{
		return $this->city_name;
	}

	public function setLong(float $longitude): static{
		$this->longitude = $longitude;
		return $this;
	}

	public function getLong(): float{
		return $this->longitude;
	}

	public function setLat(float $latitude): static{
		$this->latitude = $latitude;
		return $this;
	}
}
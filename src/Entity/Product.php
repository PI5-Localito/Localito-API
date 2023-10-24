<?php

namespace App\Entity;

use App\Storage\StorageResultParseableTrait;

class Product extends Base{
	use StorageResultParseableTrait;

	protected int $id;
	protected int $stand_id;
	protected string $name;
	protected float $price;

	public function setProduct(Product $product): static{
		$this->id = $product->id();
		return $this;
	}

	public function getProduct(bool $raw = false): int|Product{
		return $raw ? $this->id : new Product($this->stand_id);
	}

	public function setName(string $name): static{
		$this->name = $name;
		return $this;
	}

	public function getName(): string{
		return $this->name;
	}

	public function setPrice(float $price): static{
		$this->price = $price;
		return $this;
	}

	public function getPrice(): float{
		return $this->price;
	}
}
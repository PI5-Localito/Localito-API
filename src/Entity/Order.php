<?php

namespace App\Entity;

use App\Storage\StorageResultParseableTrait;

class Order extends Base{
	use StorageResultParseableTrait;

	protected int $id;
	protected int $buyer_id;
	protected int $seller_id;
	protected int $stand_id;
	protected string $date;
	protected string $state;

	public function setOrder(Order $order): static{
		$this->id = $order->id();
		return $this;
	}

	public function getOrder(bool $raw = false): int|Order{
		return $raw ? $this->id : new Order($this->id);
	}

	public function setBuyer(Buyer $buyer): static{
		$this->buyer_id = $buyer->id();
		return $this;
	}

	public function getBuyer(bool $raw = false): int|Buyer{
		return $raw ? $this->buyer_id : new Buyer($this->buyer_id);
	}

	public function setSeller(Seller $seller): static{
		$this->seller_id = $seller->id();
		return $this;
	}

	public function getSeller(bool $raw = false): int|Seller{
		return $raw ? $this->seller_id : new Seller($this->seller_id);
	}

	public function setStand(Stand $stand): static{
		$this->stand_id = $stand->id();
		return $this;
	}

	public function getStand(bool $raw = false): int|Stand{
		return $raw ? $this->stand_id : new Stand($this->stand_id);
	}

	public function setDate(string $date): static{
		$this->date = $date;
		return $this;
	}

	public function getDate(): string{
		return $this->date;
	}

	public function setState(string $state): static{
		$this->state = $state;
		return $this;
	}

	public function getState(): string{
		return $this->state;
	}
}
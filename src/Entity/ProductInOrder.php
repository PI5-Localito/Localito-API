<?php

namespace App\Entity;

use App\Storage\StorageResultParseableTrait;

class ProductInOrder extends Base{
	use StorageResultParseableTrait;

	protected int $order_id;
	protected int $product_id;
	protected int $product_quantity;

	public function setOrder(Order $order): static{
		$this->order_id = $order->id();
		return $this;
	}

	public function getOrder(bool $raw = false): int|Order{
		return $raw ? $this->order_id : new Order($this->order_id);
	}

	public function setProduct(Product $product): static{
		$this->product_id = $product->id();
		return $this;
	}

	public function getProduct(bool $raw = false): int|Product{
		return $raw ? $this->product_id : new Product($this->product_id);
	}

	public function setProductQuantity(int $product_quantity): static{
		$this->product_quantity = $product_quantity;
		return $this;
	}

	public function getProductQuantity(): int{
		return $this->product_quantity;
	}
}
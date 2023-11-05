<?php

namespace App\Entity;

use Lib\Storage\Entity;
use Lib\Storage\Traits\MethodHydrator;

class ProductInOrder implements Entity
{
    use MethodHydrator;

    protected int $orderId;
    protected int $productId;
    protected int $productQuantity;
    
    /**
     * @return array<string,array>
     */
    public function mapping(): array{
        return [
        'order_id' => [$this->getOrder, $this->setOrder],
        'product_id' => [$this->getProduct, $this->setgetProduct],
        'product_quantity' => [$this->getProductQuantity, $this->getProductQuantity],
        ];
    }

    public function getId(): ?int{
        return null;
    }

    public function setOrder(int $oid): static
    {
        $this->orderId = $oid;
        return $this;
    }

    public function getOrder(): int
    {
        return $this->orderId;
    }

    public function setProduct(int $pid): static
    {
        $this->productId = $pid;
        return $this;
    }

    public function getProduct(): int
    {
        return $this->productId;
    }

    public function setProductQuantity(int $productQuantity): static
    {
        $this->productQuantity = $productQuantity;
        return $this;
    }

    public function getProductQuantity(): int
    {
        return $this->productQuantity;
    }
}


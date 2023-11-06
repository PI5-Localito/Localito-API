<?php

namespace App\Entity;

use Lib\Storage\AbstractEntity;
use Lib\Storage\Annotations\Column;
use Lib\Storage\Traits\AnnotationColumns;
use Lib\Storage\Traits\ColumnHydrate;
use Symfony\Component\Validator\Constraints as Assert;

class ProductInOrder extends AbstractEntity
{
    use AnnotationColumns;
    use ColumnHydrate;

    #[Column('order_id')]
    protected int $orderId;

    #[Column('product_id')]
    protected int $productId;

    #[Column('product_quantity')]
    protected int $productQuantity;

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

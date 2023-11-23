<?php

namespace App\Entity;

use App\Model\ProductInOrderRepo;
use Lib\Storage\AbstractEntity;
use Lib\Storage\Annotations\Column;
use Lib\Storage\Annotations\Table;
use Lib\Storage\Traits\AnnotationMappings;
use Lib\Storage\Traits\ColumnHydrate;
use Symfony\Component\Validator\Constraints as Assert;

#[Table('products_in_orders', ProductInOrderRepo::class)]
class ProductInOrder extends AbstractEntity
{
    use AnnotationMappings;
    use ColumnHydrate;

    #[Column('order_id')]
    public int $orderId;

    #[Column('product_id')]
    public int $productId;

    #[Column('product_quantity')]
    public int $productQuantity;

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

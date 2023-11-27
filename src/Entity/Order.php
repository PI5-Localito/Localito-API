<?php

namespace App\Entity;

use App\Model\OrderRepo;
use Lib\Storage\AbstractEntity;
use Lib\Storage\Annotations\Column;
use Lib\Storage\Annotations\Table;
use Lib\Storage\Traits\AnnotationMappings;
use Lib\Storage\Traits\ColumnHydrate;

#[Table('orders', OrderRepo::class)]
class Order extends AbstractEntity
{
    use AnnotationMappings;
    use ColumnHydrate;

    #[Column('buyer_id')]
    public int $buyerId;

    #[Column('seller_id')]
    public int $sellerId;

    #[Column('stand_id')]
    public int $standId;

    #[Column('date')]
    public string $date = date();

    #[Column('state')]
    public string $state = 'PENDING';

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setBuyer(int $buyerId): static
    {
        $this->buyerId = $buyerId;
        return $this;
    }

    public function getBuyer(): int
    {
        return $this->buyerId;
    }

    public function setSeller(int $sellerId): static
    {
        $this->sellerId = $sellerId;
        return $this;
    }

    public function getSeller(): int
    {
        return $this->sellerId;
    }

    public function setStand(int $standId): static
    {
        $this->standId = $standId;
        return $this;
    }

    public function getStand(): int
    {
        return $this->standId;
    }

    public function setDate(string $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setState(string $state): static
    {
        $this->state = $state;
        return $this;
    }

    public function getState(): string
    {
        return $this->state;
    }
}

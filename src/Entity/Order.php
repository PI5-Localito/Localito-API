<?php

namespace App\Entity;

use Lib\Storage\Entity;
use Lib\Storage\Traits\MethodHydrator;

class Order implements Entity
{
    use MethodHydrator;

    protected ?int $id;
    protected int $buyerId;
    protected int $sellerId;
    protected int $standId;
    protected string $date;
    protected string $state;
    
    /**
     * @return array<string,array>
     */
    public function mappings(): array{
        return [
            'id' => [$this->getId, $this->setId],
            'buyer_id' => [$this->getBuyer, $this->setBuyer],
            'seller_id' => [$this->getSeller, $this->setSeller],
            'stand_id' => [$this->getStand, $this->setStand],
            'date' => [$this->getDate, $this->setDate],
            'state' => [$this->getState, $this->setState]
        ];
    }
    /**
     * @param mixed $id
     */
    public function setId($id): Order
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setBuyer(int $uid): static
    {
        $this->buyerId = $uid;
        return $this;
    }

    public function getBuyer(): int
    {
        return $this->buyerId;
    }

    public function setSeller(int $uid): static
    {
        $this->sellerId = $uid;
        return $this;
    }

    public function getSeller(): int
    {
        return $this->sellerId;
    }

    public function setStand(int $sid): static
    {
        $this->standId = $sid;
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


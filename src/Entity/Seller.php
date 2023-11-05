<?php

namespace App\Entity;

use Lib\Storage\Entity;
use Lib\Storage\Traits\MethodHydrator;

class Seller implements Entity
{
    use MethodHydrator;

    protected ?int $id;
    protected int $userId;
    protected bool $state;

    /**
     * @return array<string,array>
     */
    public function mappings(): array{
        return [
            'id' => [$this->getId, $this->setId],
            'user_id' => [$this->getUser, $this->setUser],
            'state' => [$this->getState, $this->setState],
        ];
    }

    public function setId(int $id): static{
        $this->id = $id;
        return $this;
    }

    public function getId(): int{
        return $this->id;
    }

    public function setUser(int $uid): static
    {
        $this->userId = $uid;
        return $this;
    }

    public function getUser(): int
    {
        return $this->userId;
    }

    /** State */

    public function setState(bool $state): static
    {
        $this->state = $state;
        return $this;
    }

    public function getState(): string{
        return $this->state;
    }

    public function toggleState(): static
    {
        $this->state = !$this->state;
        return $this;
    }
}

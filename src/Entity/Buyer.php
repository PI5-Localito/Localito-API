<?php

namespace App\Entity;

use Lib\Storage\AbstractEntity;
use Lib\Storage\Annotations\Column;
use Lib\Storage\Traits\ColumnHydrate;
use Lib\Storage\Traits\ColumnMappings;
use Lib\Storage\Traits\ColumnSerialize;

class Buyer extends AbstractEntity
{
    use ColumnSerialize;
    use ColumnMappings;
    use ColumnHydrate;

    #[Column('user_id')]
    public int $userId;

    #[Column('state')]
    protected bool $state;

    public function setUser(int $uid): static
    {
        $this->userId = $uid;
        return $this;
    }

    public function getUser(): int
    {
        return $this->userId;
    }

    public function setState(bool $state): static
    {
        $this->state = $state;
        return $this;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function toggleState(): static
    {
        $this->state = !$this->state;
        return $this;
    }
}

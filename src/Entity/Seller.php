<?php

namespace App\Entity;

use Lib\Storage\AbstractEntity;
use Lib\Storage\Annotations\Column;
use Lib\Storage\Traits\AnnotationMappings;
use Lib\Storage\Traits\ColumnHydrate;
use Symfony\Component\Validator\Constraints as Assert;

class Seller extends AbstractEntity
{
    use ColumnHydrate;
    use AnnotationMappings;

    #[Column('user_id')]
    protected int $userId;

    #[Column('state')]
    protected bool $state;

    /**
     * @return array<string,array>
     */
    public function mappings(): array
    {
        return [
            'id' => [$this->getId, $this->setId],
            'user_id' => [$this->getUser, $this->setUser],
            'state' => [$this->getState, $this->setState],
        ];
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): int
    {
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

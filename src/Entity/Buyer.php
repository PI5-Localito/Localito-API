<?php

namespace App\Entity;

use Lib\Storage\AbstractEntity;
use Lib\Storage\Annotations\Column;
use Lib\Storage\Traits\AnnotationMappings;
use Lib\Storage\Traits\ColumnHydrate;
use Symfony\Component\Validator\Constraints as Assert;

class Buyer extends AbstractEntity
{
    use AnnotationMappings;
    use ColumnHydrate;

    #[Column('user_id')]
    #[Assert\NotBlank(message: 'not.blank')]
    #[Assert\NotNull(message: 'not.null')]
    #[Assert\Type(type: 'int', message: 'type.digit')]
    public int $userId;

    #[Column('state')]
    #[Assert\NotBlank(message: 'not.blank')]
    #[Assert\NotNull(message: 'not.null')]
    #[Assert\Type(type: 'digit', message: 'type.digit')]
    protected bool $state = true;

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

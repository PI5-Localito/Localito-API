<?php

namespace App\Entity;

abstract class Base
{
    protected ?int $id = null;

    public function __construct(int $id = null)
    {
        $this->id = $id;
    }

    final public function id(): ?int
    {
        return $this->id ?? null;
    }
}

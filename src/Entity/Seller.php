<?php

namespace App\Entity;

class Seller extends Base
{
    use StorageResultParseableTrait;

    protected int $user_id;
    protected bool $state;

    /** User */

    public function setUser(User $user): static
    {
        $this->user_id = $user->id();
        return $this;
    }

    public function getUser(bool $raw = false): int|User
    {
        return $raw ? $this->user_id : new User($this->user_id);
    }

    /** State */

    public function setState(bool $state): static
    {
        $this->state = $state;
        return $this;
    }

    public function toggleState(): static
    {
        $this->state = !$this->state;
        return $this;
    }
}

<?php

namespace App\Storage;

interface StorageInterface
{
    /**
     * Set the entity model that we are going to use
     * @param string $model
     */
    public function setModel(string $model): static;
    public function new(object $entity): bool;
    public function delete(object $entity): bool;
    public function update(object $entity): bool;
}

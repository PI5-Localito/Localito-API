<?php

namespace App\Storage;

use Traversable;

interface StorageModelInterface
{
    /**
     * Create a new entity in the model
     * @param Traversable $data
     * @return array<string, array>
     */
    public function save(Traversable $data): array;

    /**
     * Delete the entity in the model
     * @return string
     */
    public function delete(): string;
}

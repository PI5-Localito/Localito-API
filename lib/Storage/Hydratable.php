<?php

namespace Lib\Storage;

interface Hydratable
{
    /**
     * Hydrate the current entity from an array
     * @param array<string, mixed> $values
     *
     * @return void
     */
    public function hydrate(array $values): void;

    /**
     * Extract the data as an array
     *
     * @return array<string, mixed>
     */
    public function extract(): array;
}

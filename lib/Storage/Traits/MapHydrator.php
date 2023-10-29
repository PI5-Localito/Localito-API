<?php

namespace Lib\Storage\Traits;

use Lib\Storage\HydratableEntity;

/**
 * Hydrate the entity using mappings from the array
 *
 * @implements HydratableEntity
 */
trait MapHydrator
{
    /**
     * Get the mappings for the hydratation
     *
     * @return array<string, string>
     */
    abstract public function mappings(): array;

    /**
     * Hydrate the entity using the mappings
     *
     * @param array<string, mixed> $value
     */
    public function hydrate(array $value): void
    {
        foreach($this->mappings() as $key => $param) {
            $this->$param = $value[$key] ?? null;
        }
    }

    public function extract(): array
    {
        $values = [];

        foreach($this->mappings() as $key => $param) {
            $values[$key] = $this->$param;
        }

        return $values;
    }
}

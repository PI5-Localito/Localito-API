<?php

namespace Lib\Storage\Traits;

use Lib\Storage\HydratableEntity;

/**
 * Hydrate the entities using a getter and setter
 *
 * @implements HydratableEntity
 */
trait MethodHydrator
{
    /**
     * Get the methods that will be used to hydrate
     *
     * @return array<string, array>
     */
    abstract public function mappings(): array;

    /**
     * Hydrate the entity using methods
     * @param array<string, mixed> $values
     *
     * @return void
     */
    public function hydrate(array $values): void
    {
        foreach($this->mappings() as $key => $method) {
            $method[1]($values[$key] ?? null);
        }
    }

    /**
     * Extract the values as an array
     *
     * @return array<string, mixed>
     */
    public function extract(): array
    {
        $values = [];
        foreach($this->mappings() as $key => $methods) {
            $values[$key] = $methods[0]();
        }

        return $values;
    }
}

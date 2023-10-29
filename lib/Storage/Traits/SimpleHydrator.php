<?php

namespace Lib\Storage\Traits;

use Lib\Storage\HydratableEntity;

/**
 * Simple hydrate entities using the keys in the values
 *
 * @implements HydratableEntity
 */
trait SimpleHydrator
{
    /**
     * Hydrate the entity setting it's properties
     *
     * @param array<string, mixed> $values
     *
     * @return void
     */
    public function hydrate(array $values): void
    {
        foreach($values as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function extract(): array
    {
        $values = [];
        foreach($this as $key => $value) {
            $values[$key] = $value;
        }
        return $values;
    }
}

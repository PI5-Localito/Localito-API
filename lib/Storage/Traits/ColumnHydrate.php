<?php

namespace Lib\Storage\Traits;

use Lib\Storage\Annotations\Column;
use Lib\Storage\AbstractEntity;

trait ColumnHydrate
{
    /**
     * @return array<int, Column>
     */
    abstract protected function getMappings(AbstractEntity $entity): array;

    /**
     * Extract the values from the entity
     *
     * @return array<string, mixed>
     */
    protected function extract(AbstractEntity $entity): array
    {
        $values = [];
        $mappings = $this->getMappings($entity);
        foreach($mappings as $name => $column) {
            $getter = $column->getGetter();
            $data = empty($getter) ? $entity->$name ?? null : $entity->$getter();
            $values[$column->getColumn] = $data;
        }
        return $values;
    }

    /**
     * Hydrate the enttiy
     * @param array<int,mixed> $data
     *
     * @return void
     */
    protected function hydrate(AbstractEntity $entity, array $data): void
    {
        $mappings = $this->getMappings($entity);
        foreach($mappings as $name => $column) {
            $value = $data[$column->getColumn()];
            $setter = $column->getSetter();
            if (empty($setter)) {
                $entity->$name = $value;
            } else {
                $entity->$setter($value);
            }
        }
    }
}

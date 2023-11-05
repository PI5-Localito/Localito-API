<?php

namespace Lib\Storage\Traits;

use Lib\Storage\Annotations\Column;

trait ColumnHydrate
{
    /**
     * @return array<string, Column>
     */
    abstract public function getMappings(): array;

    /**
     * Extract the values from the entity
     *
     * @return array<string, mixed>
     */
    public function extract(): array
    {
        $values = [];
        $mappings = $this->getMappings();
        foreach($mappings as $name => $column) {
            $getter = $column->getGetter();
            $data = empty($getter) ? $this->$name ?? null : $this->$getter();
            $values[$column->getColumn()] = $data;
        }
        return $values;
    }

    /**
     * Hydrate the enttiy
     * @param array<int,mixed> $data
     *
     * @return void
     */
    public function hydrate(array $data): void
    {
        $mappings = $this->getMappings();
        foreach($mappings as $name => $column) {
            $value = $data[$column->getColumn()];
            $setter = $column->getSetter();
            if (empty($setter)) {
                $this->$name = $value;
            } else {
                $this->$setter($value);
            }
        }
    }
}

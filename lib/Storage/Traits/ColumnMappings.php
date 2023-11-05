<?php

namespace Lib\Storage\Traits;

use Lib\Storage\Annotations\Transform;
use Lib\Storage\Annotations\Column;
use ReflectionClass;
use Lib\Storage\AbstractEntity;

trait ColumnMappings
{
    /**
     * Get the mappings from reflection
     * @param AbstractEntity $entity
     *
     * @return array<string, Transform>
     */
    protected function getMappings(AbstractEntity $entity): array
    {
        $values = [];
        $reflection = new ReflectionClass($entity::class);
        $properties = $reflection->getProperties();

        foreach($properties as $property) {
            $column = $property->getAttributes(Column::class)[0]?->newInstance();
            $values[$property->getName()] = $column;
        }
        return array_filter($values, fn ($e) => !empty($e));
    }

    /**
     * Get the mappings of an entty exluding some props
     * @param AbstractEntity $entity
     * @param array<int, string> $exclude
     *
     * @return array<int, Column>
     */
    protected function excludeMappigns(AbstractEntity $entity, array $exclude): array
    {
        $mappings = $this->getMappings($entity);
        $filter = fn ($name) => !in_array($name, $exclude);
        return array_filter($mappings, $filter, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Get the mappings of an entty with only the specified
     * @param AbstractEntity $entity
     * @param array<int, string> $include
     *
     * @return array<string, Column>
     */
    protected function includeMapping(AbstractEntity $entity, array $include): array
    {
        $mappings = $this->getMappings($entity);
        $filter = fn ($name) => in_array($name, $include);
        return array_filter($mappings, $filter, ARRAY_FILTER_USE_KEY);
    }
}

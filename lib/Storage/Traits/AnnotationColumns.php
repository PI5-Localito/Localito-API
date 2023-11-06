<?php

namespace Lib\Storage\Traits;

use Lib\Storage\Annotations\Column;
use ReflectionClass;

trait AnnotationColumns
{
    /**
     * Get the mappings from reflection
     *
     * @return array<string, Column>
     */
    public function getMappings(): array
    {
        $values = [];
        $reflection = new ReflectionClass($this::class);
        $properties = $reflection->getProperties();

        foreach($properties as $property) {
            $column = $property->getAttributes(Column::class)[0]?->newInstance();
            $values[$property->getName()] = $column;
        }
        return array_filter($values, fn ($e) => !empty($e));
    }

    /**
     * Get the mappings of an entty exluding some props
     * @param array<int, string> $exclude
     *
     * @return array<int, Column>
     */
    public function excludeMappigns(array $exclude): array
    {
        $mappings = $this->getMappings();
        $filter = fn ($name) => !in_array($name, $exclude);
        return array_filter($mappings, $filter, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Get the mappings of an entty with only the specified
     * @param array<int, string> $include
     *
     * @return array<string, Column>
     */
    public function includeMapping(array $include): array
    {
        $mappings = $this->getMappings();
        $filter = fn ($name) => in_array($name, $include);
        return array_filter($mappings, $filter, ARRAY_FILTER_USE_KEY);
    }
}

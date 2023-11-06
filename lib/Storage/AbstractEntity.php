<?php

namespace Lib\Storage;

use JsonSerializable;
use Lib\Storage\Annotations\Column;
use PDO;

abstract class AbstractEntity implements JsonSerializable
{
    #[Column('id', type: PDO::PARAM_INT)]
    public ?int $id = null;

    /**
     * Extract the entity values as an array
     * @return array<string, mixed>
     */
    abstract public function extract(): array;

    /**
     * Set the entity data from an array
     *
     * @param array<int,mixed> $data
     */
    abstract public function hydrate(array $data): void;

    /**
     * Get the mappings of this entity
     *
     * @return array<int,Column>
     */
    abstract public function getMappings(): array;

    /**
     * Exclude mappings using an array
     *
     * @param array<int,string> $exclude
     *
     * @return array<int,Column>
     */
    abstract public function excludeMappigns(array $exclude): array;

    /**
     * Retrive only the desired mappings
     *
     * @param array<int,string> $include
     *
     * @return array<int,Column>
     */
    abstract public function includeMapping(array $include): array;

    /**
     * Get the values for json serializaton
     */
    public function jsonSerialize(): mixed
    {
        return $this->extract();
    }
}

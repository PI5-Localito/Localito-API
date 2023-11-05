<?php

namespace Lib\Storage;

use JsonSerializable;
use Lib\Storage\Annotations\Column;
use PDO;

abstract class AbstractEntity implements JsonSerializable
{
    #[Column('id', type: PDO::PARAM_INT)]
    public ?int $id = null;

    abstract public function jsonSerialize(): mixed;
    abstract public function extract(): array;
    abstract public function hydrate(array $data): void;
    abstract public function getMappings(): array;
    abstract public function excludeMappigns(array $exclude): array;
    abstract public function includeMapping(array $include): array;
}

<?php

namespace Lib\Storage\Annotations;

use Attribute;
use PDO;

#[Attribute]
class Column
{
    public function __construct(
        protected string $column,
        protected ?string $get = null,
        protected ?string $set = null,
        protected string $type = PDO::PARAM_STR,
    ) {
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getGetter(): ?string
    {
        return $this->get ?? null;
    }

    public function getSetter(): ?string
    {
        return $this->set ?? null;
    }
}

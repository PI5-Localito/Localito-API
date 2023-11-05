<?php

namespace Lib\Storage\Annotations;

use Attribute;

#[Attribute]
class Table
{
    public function __construct(
        protected string $table,
        protected string $model,
    ) {
    }

    public function getValue(): string
    {
        return $this->table;
    }

    public function getModel(): string
    {
        return $this->model;
    }
}

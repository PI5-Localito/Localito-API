<?php

namespace Lib\Storage\Traits;

trait ColumnSerialize
{
    /**
     * @return array<string, mixed>
     */
    abstract public function extract(): array;

    public function jsonSerialize(): mixed
    {
        return $this->extract();
    }
}

<?php

namespace App\Storage;

use App\Storage\StorageResultInterface;

trait StorageResultParseableTrait
{
    public static function fromArray(StorageResultInterface $data): static
    {
        $obj = new static();
        foreach($data as $key => $value) {
            $obj->$key = $value;
        }
        return $obj;
    }
}

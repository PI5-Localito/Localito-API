<?php

namespace App\Storage;

use Iterator;

interface StorageResultInterface extends Iterator
{
    public function raw(): mixed;
}

<?php

namespace Lib\Storage;

use Lib\Storage\Annotations\Column;
use PDO;

abstract class AbstractEntity
{
    #[Column(type: PDO::PARAM_STR)]
    public ?int $id;
}

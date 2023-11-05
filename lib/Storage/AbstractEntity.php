<?php

namespace Lib\Storage;

use Lib\Storage\Annotations\Column;
use PDO;

abstract class AbstractEntity
{
    #[Column('id', type: PDO::PARAM_INT)]
    public ?int $id = null;
}

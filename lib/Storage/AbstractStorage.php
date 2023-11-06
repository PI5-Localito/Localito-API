<?php

namespace Lib\Storage;

use Lib\Storage\Annotations\Table;
use PDO;
use ReflectionClass;

abstract class AbstractStorage
{
    /**
     * Get the database connection
     *
     * @return PDO
     */
    abstract public function getDatabase(): PDO;

    /**
     * Get the model related to the database
     *
     * @param string $entity
     */
    public function getModel(string $entity): AbstractModel
    {
        $refl = new ReflectionClass($entity);
        $table = $refl->getAttributes(Table::class)[0] ?? null;
        if (!$table) {
            return null;
        }
        $table = $table->newInstance();
        $model = $table->getModel();
        return new $model($this->getDatabase(), $entity);
    }
}

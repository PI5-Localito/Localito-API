<?php

namespace Lib\Storage;

use PDO;

interface Storage
{
    /**
     * Get the database connection
     *
     * @return PDO
     */
    public function getDatabase(): PDO;

    /**
     * Get the model related to the database
     *
     * @template T of Model
     * @param class-string<T> $model
     * @return T
     */
    public function getModel(string $model): Model;
}

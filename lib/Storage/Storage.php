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
     * @param string $entity
     */
    public function getModel(string $entity): AbstractModel;
}

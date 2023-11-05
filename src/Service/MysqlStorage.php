<?php

namespace App\Service;

use Lib\Storage\AbstractModel;
use Lib\Storage\Annotations\Table;
use Lib\Storage\Storage;
use PDO;
use ReflectionClass;

class MysqlStorage implements Storage
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = new PDO($_ENV['MYSQL_URL'], $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASS']);
    }

    public function getModel(string $entity): AbstractModel
    {
        $refl = new ReflectionClass($entity);
        $table = $refl->getAttributes(Table::class)[0] ?? null;
        if (!$table) {
            return null;
        }
        $table = $table->newInstance();
        $model = $table->getModel();
        return new $model($this, $entity);
    }

    public function getDatabase(): PDO
    {
        return $this->db;
    }
}

<?php

namespace App\Service;

use Lib\Storage\Model;
use Lib\Storage\Storage;
use PDO;

class MysqlStorage implements Storage
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = new PDO($_ENV['MYSQL_URL'], $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASS']);
    }

    public function getModel(string $model): Model
    {
        return new $model($this);
    }

    public function getDatabase(): PDO
    {
        return $this->db;
    }
}

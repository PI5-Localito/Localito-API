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
        $this->db = new PDO($_ENV['MYSQL_URL']);
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

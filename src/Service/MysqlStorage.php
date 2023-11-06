<?php

namespace App\Service;

use Lib\Storage\AbstractStorage;
use PDO;

class MysqlStorage extends AbstractStorage
{
    public function getDatabase(): PDO
    {
        return new PDO(
            $_ENV['MYSQL_URL'],
            $_ENV['MYSQL_USER'],
            $_ENV['MYSQL_PASS'],
            options: [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
                PDO::ATTR_EMULATE_PREPARES => false,
                // TODO: Work in persistency
                // PDO::ATTR_PERSISTENT => true,
            ]
        );
    }
}

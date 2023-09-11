<?php

namespace App\Service;

use Exception;
use mysqli;

class Database
{
    protected mysqli $connection;

    public function __construct()
    {
        @$this->connection = new mysqli(
            username: $_ENV['MARIADB_USER'],
            password: $_ENV['MARIADB_PASSWORD'],
            port: $_ENV['MARIADB_PORT'],
            database: 'localito'
        );

        if ($this->connection->connect_errno) {
            throw new Exception($this->connection->connect_error);
        }
    }

}

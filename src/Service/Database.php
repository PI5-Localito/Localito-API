<?php

namespace App\Service;

use mysqli;

class Database
{
    protected mysqli $connection;

    public function __construct() {
        $this->connection = new mysqli(
            username: $_ENV['DB_USER'],
            password: $_ENV['DB_PASSWORD'],
            port: $_ENV['DB_PORT'],
            database: 'localito'
        );
    }

}

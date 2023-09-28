<?php

namespace App\Service;

use mysqli;

class Mariadb extends mysqli
{
	public function __construct() {
		parent::__construct(
			$_ENV['DB_HOST'],
			$_ENV['DB_USER'],
			$_ENV['DB_PASSW'],
			$_ENV['DB_SCHEMA'],
			$_ENV['DB_PORT'],
		);
	}
}
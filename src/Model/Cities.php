<?php

namespace App\Model;

class Cities{

    /**
     * @param string $cCode
     * @param string $name
     * @param float $long
     * @param float $lat
     * @return array
     */
    public function add_city($cCode, $name, $long, $lat): array{
		return ["INSERT INTO cities(country_code, city_name, longitude, latitude) VALUES(?, ? ,? ,?)"];
	}
    /**
     * @param int $id
     * @return array
     */
    public function select_city($id): array{
		return ["SELECT * FROM cities WHERE id = ?"];
	}
}
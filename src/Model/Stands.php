<?php

namespace App\Model;

class Stands{

    /**
     * @param int $sid
     * @param string $uid
     * @param string $name
     * @param string $info
     * @param int $city
     * @return array
     */
    public function create_stand($sid, $uid, $name, $info, $city): array{
		return ["INSERT INTO stands('seller_id', 'uid', 'stand_name', 'info', 'city') VALUES(?, ?, ?, ?, ?)"];
	}
    /**
     * @param int $id
     * @return array
     */
    public  function view_stand($id): array{
		return ["SELECT * FROM stands WHERE 'id' = ?"];
	}
}
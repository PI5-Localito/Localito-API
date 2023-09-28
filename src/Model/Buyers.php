<?php

namespace App\Model;

class Buyers{

    /**
     * @param int $uid
     * @return array
     */
    public function create_buyers($uid): array{
		return ["INSERT INTO buyers('user_id') VALUES(?)"];
	}

    /**
     * @param int $id
     * @param bool $state
     * @return array
     */
    public function change_state($id, $state): array{
		return ["UPDATE sellers SET state = ? WHERE id = ?"];
	}
}
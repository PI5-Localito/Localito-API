<?php

namespace App\Model;

class Sellers{

    /**
     * @param int $uid
     * @return array
     */
    public function create_seller($uid): array{
		return ["INSERT INTO sellers('user_id') VALUES(?)"];
	}

    /**
     * @param int $id
     * @param bool $state
     * @return array
     */
    public function
     change_state($id, $state): array{
		return ["UPDATE sellers SET state = ? WHERE id = ?"];
	}
}
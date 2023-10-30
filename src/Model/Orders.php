<?php

namespace App\Model;

class Orders{
    /**
     * @param int $bid
     * @param int $sid
     * @param int $stid
     * @return array
     */
    public function create_order($bid, $sid, $stid): array{
		return ["INSERT INTO orders('buyer_id', 'seller_id', 'stand_id') VALUES(?, ?, ?)"];
	}

    /**
     * @param int $id
     * @return array
     */
    public function view_order($id): array{
		return ["SELECT * FROM orders WHERE 'id' = ?"];
	}

    /**
     * @param int $id
     * @param string $state
     * @return array<int,string>
     */
    public function change_state($id, $state): array{
		return ["UPDATE orders SET 'state' = ? WHERE id = ?"];
	}

    /**
     * @param int $id
     * @return array
     */
    public function delete_order($id): array{
		return ["DELETE FROM orders WHERE 'id' = ?"];
	}
}
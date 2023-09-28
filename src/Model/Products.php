<?php

namespace App\Model;

class Products{
    /**
     * @param int $sid
     * @param string $name
     * @param float $price
     * @return array
     */
    public function create_product($sid, $name, $price): array{
		return ["INSERT INTO products('stand_id', 'name', 'price') VALUES(?, ?, ?)"];
	}
    /**
     * @param int $id
     * @param int $sid
     * @param string $name
     * @param float $price
     * @return array
     */
    public function edit_product($id, $sid, $name, $price): array{
		return ["UPDATE products SET 'stand_id', = ?, 'name' = ?, 'price' = ? WHERE 'id' = ?"];
	}
}
<?php

namespace App\Model;

class ProductsInOrder{
    /**
     * @param int $oid
     * @param int $pid
     * @return array
     */
    public function add_product($oid, $pid): array{
		return ["INSERT INTO products_in_orders('order_id', 'product_id') VALUES(?, ?)"];
	}

    /**
     * @param int $oid
     * @param int $pid
     * @return array
     */
    public function add_one($oid, $pid): array{
		return ["UPDATE products_in_orders SET 'product_quantity' = 'product_quantity' + 1 WHERE 'order_id' = ? AND 'product_id' = ?"];

	}

	/**
     * @param int $oid
     * @param int $pid
     * @return array
     */
    public function remove_one($oid, $pid): array{
		return ["UPDATE products_in_orders SET 'product_quantity' = 'product_quantity' - 1 WHERE 'order_id' = ? AND 'product_id' = ?"];

	}

    /**
     * @param int $oid
     * @param int $pid
     * @return array
     */
    public function remove_product($oid, $pid): array{
		return ["DELETE FROM products_in_orders WHERE 'order_id' = ? AND 'product_id' = ?"];
	}

	/**
     * @param int $oid
     * @param int $pid
     * @return array
     */
    public function remove_all($oid): array{
		return ["DELETE FROM products_in_orders WHERE 'order_id' = ?"];
	}
}


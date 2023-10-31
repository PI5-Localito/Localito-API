<?php

namespace App\Model;

use App\Entity\ProductInOrder;
use Lib\Storage\Entity;
use Lib\Storage\Model;
use Lib\Storage\Traits\DefaultModel;
use PDO;
use PDOStatement;

class ProductsInOrder{

	use DefaultModel;

	/**
     * @param PDOStatement $stmt
     * @param ProductInOrder $entity
     *
     * @return void
     */
	public function bindValues(PDOStatement $stmt, Entity $entity): void
	{
		$stmt->bindValue('bundle__orderId', $entity->getOrder(), PDO::PARAM_INT);
		$stmt->bindValue('bundle__productId', $entity->getProduct(), PDO::PARAM_INT);
		$stmt->bindValue('bundle__productQuantity', $entity->getProductQuantity(), PDO::PARAM_INT);
	}

	public function all(int $limit = 0, int $offset = 0, int $page = 0): array
	{
		$stmt = $this->getAll($limit, $offset, $page);
        $entities = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $entity = new ProductInOrder();
            $entities[] = $entity->hydrate($row);
        }

        return $entities;
	}

	public function get(int $id): ?ProductInOrder
    {
        $row = $this->getByID($id);
        if($row === false){
            return null;
        }
        $entity = new ProductInOrder();
        $entity->hydrate($row);
    }

    /**
     * @param ProductInOrder $entity
     *
     * @return bool
     */
    public function save(Entity $entity): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO `{$this->getTable()}` (" .
                'order_id, ' .
                'product_id, ' .
                'product_quantity ' .
            ') VALUES (' .
                ':bundle__orderId, ' .
                ':bundle__productId,' .
                ':bundle__productQuantity' .
            ')'
        );
        $this->bindValues($stmt, $entity);
        $stmt->execute();
        $id = $this->db->lastInsertId();
        //$entity->setId($id);
        return true;
    }

    /**
     * @param ProductInOrder $entity
     *
     * @return bool
     */
    public function update(Entity $entity): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE `{$this->getTable()}` SET " .
            'order_id = :bundle__orderId, ' .
            'product_id = :bundle__productId, ' .
            'product_quantity = :bundle__productQuantity ' .
            'WHERE id = ?'
        );
        $this->bindValues($stmt, $entity);
        //$this->bindValues(0, $entity->getId());
        $stmt->execute();
        return true;
    }

    /**
     * @param ProductInOrder $entity
     *
     * @return bool
     */
    public function delete(Entity $entity): bool
    {
        $stmt = $this->db->prepare("DELETE FROM `{$this->getTable()}` WHERE id=?");
        $stmt->bindValue(0, $entity->getId());
        return $stmt->execute();
    }

    public static function getTable(): string
    {
        return 'products_in_orders';
    }
}


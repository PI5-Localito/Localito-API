<?php

namespace App\Model;

use App\Entity\Order;
use Lib\Storage\Entity;
use Lib\Storage\Model;
use Lib\Storage\Traits\DefaultModel;
use PDO;
use PDOStatement;

class Orders{
    use DefaultModel;

    /**
     * @param PDOStatement $stmt
     * @param Order $entity
     *
     * @return void
     */
    public function bindValues(PDOStatement $stmt, Entity $entity): void
    {
        $stmt->bindValue('bundle__buyerId', $entity->getBuyer(), PDO::PARAM_INT);
        $stmt->bindValue('bundle__sellerId', $entity->getSeller(), PDO::PARAM_INT);
        $stmt->bindValue('bundle__standId', $entity->getSeller(), PDO::PARAM_INT);
        $stmt->bindValue('bundle__date', $entity->getDate(), PDO::PARAM_STR);
        $stmt->bindValue('bundle__state', $entity->getState(), PDO::PARAM_STR);
    }

    public function all(int $limit = 0, int $offset = 0, int $page = 0): array
    {
        $stmt = $this->getAll($limit, $offset, $page);
        $entities = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $entity = new Order();
            $entities[] = $entity->hydrate($row);
        }

        return $entities;
    }

    public function get(int $id): ?Order
    {
        $row = $this->getByID($id);
        if($row === false){
            return null;
        }
        $entity = new Order();
        $entity->hydrate($row);
    }

    /**
     * @param Order $entity
     *
     * @return bool
     */
    public function save(Entity $entity): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO `{$this->getTable()}` (" .
                'buyer_id, ' .
                'seller_id, ' .
                'stand_id, ' .
                'date, ' .
                'state' .
            ') VALUES (' .
                ':bundle__buyerId, ' .
                ':bundle__sellerId,' .
                ':bundle__standId,' .
                ':bundle__date,' .
                ':bundle__state' .
            ')'
        );
        $this->bindValues($stmt, $entity);
        $stmt->execute();
        $id = $this->db->lastInsertId();
        $entity->setId($id);
        return true;
    }

    /**
     * @param Order $entity
     *
     * @return bool
     */
    public function update(Entity $entity): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE `{$this->getTable()}` SET " .
            'buyer_id = :bundle__buyerId, ' .
            'seller_id = :bundle__sellerId, ' .
            'stand_id = :bundle__standId, ' .
            'date = :bundle__date, ' .
            'state = :bundle__state ' .
            'WHERE id = ?'
        );
        $this->bindValues($stmt, $entity);
        $this->bindValues(0, $entity->getId());
        $stmt->execute();
        return true;
    }

    /**
     * @param Order $entity
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
        return 'orders';
    }
}
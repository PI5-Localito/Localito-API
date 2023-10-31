<?php

namespace App\Model;

use App\Entity\Stand;
use Lib\Storage\Entity;
use Lib\Storage\Model;
use Lib\Storage\Traits\DefaultModel;
use PDO;
use PDOStatement;

class Stands
{
    use DefaultModel;

    /**
     * @param PDOStatement $stmt
     * @param Stand $entity
     *
     * @return void
     */
    public function bindValues(PDOStatement $stmt, Entity $entity): void
    {
        $stmt->bindValue('bundle__sellerId', $entity->getSeller(), PDO::PARAM_INT);
        $stmt->bindValue('bundle__tag', $entity->getTag(), PDO::PARAM_STR);
        $stmt->bindValue('bundle__name', $entity->getName(), PDO::PARAM_STR);
        $stmt->bindValue('bundle__info', $entity->getInfo(), PDO::PARAM_STR);
        $stmt->bindValue('bundle__cityId', $entity->getCity(), PDO::PARAM_INT);
    }

    public function all(int $limit = 0, int $offset = 0, int $page = 0): array
    {
        $stmt = $this->getAll($limit, $offset, $page);
        $entities = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $entity = new Stand();
            $entities[] = $entity->hydrate($row);
        }

        return $entities;
    }

    public function get(int $id): ?Stand
    {
        $row = $this->getByID($id);
        if ($row === false) {
            return null;
        }
        $entity = new Stand();
        $entity->hydrate($row);
        return $entity;
    }

    /**
     * @param Stand $entity
     *
     * @return bool
     */
    public function save(Entity $entity): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO `{$this->getTable()}` (" .
                'seller_id,' .
                'tag, ' .
                'stand_name, ' .
                'info, ' .
                'city ' .
            ') VALUES (' .
                ':bundle__sellerId, ' .
                ':bundle__tag, ' .
                ':bundle__name, ' .
                ':bundle__info, ' .
                ':bundle__cityId, ' .
                ')'
        );
        $this->bindValues($stmt, $entity);
        $stmt->execute();
        $id = $this->db->lastInsertId();
        $entity->setId($id);
        return true;
    }

    /**
     * @param Stand $entity
     *
     * @return bool
     */
    public function update(Entity $entity): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE `{$this->getTable()}` SET " . 
                'seller_id = :bundle__sellerId, ' .
                'tag = :bundle__tag, ' .
                'stand_name = :bundle__name, ' .
                'info = :bundle__info, ' .
                'city = :bundle__cityId, ' .
            'WHERE id = ?'
        );
        $this->bindValues($stmt, $entity);
        $this->bindValues(0, $entity->getId());
        $stmt->execute();
        return true;
    }

    /**
     * @param Stand $entity
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
        return 'stands';
    }
}
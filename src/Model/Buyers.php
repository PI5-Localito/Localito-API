<?php

namespace App\Model;

use App\Entity\Buyer;
use Lib\Storage\Entity;
use Lib\Storage\Model;
use Lib\Storage\Traits\DefaultModel;
use PDO;
use PDOStatement;

class Buyers implements Model
{
    use DefaultModel;

    /**
     * @param PDOStatement $stmt
     * @param Buyer $entity
     *
     * @return void
     */
    public function bindValues(PDOStatement $stmt, Entity $entity): void
    {
    	$stmt->bindValue('bundle__userId', $entity->getUser(), PDO::PARAM_INT);
        $stmt->bindValue('bundle__state', $entity->getState(), PDO::PARAM_STR);
    }

    public function all(int $limit = 0, int $offset = 0, int $page = 0): array
    {
        $stmt = $this->getAll($limit, $offset, $page);
        $entities = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $entity = new Buyer();
            $entities[] = $entity->hydrate($row);
        }

        return $entities;
    }

    public function get(int $id): ?Buyer
    {
        $row = $this->getByID($id);
        if ($row === false) {
            return null;
        }
        $entity = new Buyer();
        $entity->hydrate($row);
        return $entity;
    }

    /**
     * @param Buyer $entity
     *
     * @return bool
     */
    public function save(Entity $entity): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO `{$this->getTable()}` (" .
                'user_id,' .
                'state' .
            ') VALUES (' .
                ':bundle__userId, ' .
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
     * @param Buyer $entity
     *
     * @return bool
     */
    public function update(Entity $entity): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE `{$this->getTable()}` SET " . 
                'user_id = :bundle__userId, ' .
                'state = :bundle__state' .
            'WHERE id = ?'
        );
        $this->bindValues($stmt, $entity);
        $this->bindValues(0, $entity->getId());
        $stmt->execute();
        return true;
    }

    /**
     * @param Entity $entity
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
        return 'buyers';
    }
}

<?php

namespace App\Model;

use App\Entity\Message;
use Lib\Storage\Entity;
use Lib\Storage\Model;
use Lib\Storage\Traits\DefaultModel;
use PDO;
use PDOStatement;

class Messages implements Model{

    use DefaultModel;

    /**
     * @param PDOStatement $stmt
     * @param Message $entity
     *
     * @return void
     */
    public function bindValues(PDOStatement $stmt, Entity $entity): void
    {
        $stmt->bindValue('bundle__userFrom', $entity->getFrom(), PDO::PARAM_INT);
        $stmt->bindValue('bundle__userTo', $entity->getTo(), PDO::PARAM_INT);
        $stmt->bindValue('bundle__orderId', $entity->getOrder(), PDO::PARAM_INT);
        $stmt->bindValue('bundle__body', $entity->getBody(), PDO::PARAM_STR);
        $stmt->bindValue('bundle__timestamp', $entity->getTimestamp(), PDO::PARAM_STR);
    }
    
    public function all(int $limit = 0, int $offset = 0, int $page = 0): array
    {
        $stmt = $this->getAll($limit, $offset, $page);
        $entities = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $entity = new Message();
            $entities[] = $entity->hydrate($row);
        }

        return $entities;
    }

    public function get(int $id): ?Message
    {
        $row = $this->getByID($id);
        if($row === false){
            return null;
        }
        $entity = new Message();
        $entity->hydrate($row);
    }

    /**
     * @param Message $entity
     *
     * @return bool
     */
    public function save(Entity $entity): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO `{$this->getTable()}` (" .
                'user_from, ' .
                'user_to, ' .
                'order_id, ' .
                'body, ' .
                'message_timestamp' .
            ') VALUES (' .
                ':bundle__userFrom, ' .
                ':bundle__userTo,' .
                ':bundle__orderId,' .
                ':bundle__body,' .
                ':bundle__timestamp' .
            ')'
        );
        $this->bindValues($stmt, $entity);
        $stmt->execute();
        $id = $this->db->lastInsertId();
        $entity->setId($id);
        return true;
    }

    /**
     * @param Message $entity
     *
     * @return bool
     */
    public function update(Entity $entity): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE `{$this->getTable()}` SET " . 
                'user_from = :bundle__userFrom, ' .
                'user_to = :bundle__userTo, ' .
                'order_id = :bundle__orderId, ' .
                'body = :bundle__body, ' .
                'message_timestamp = :bundle__timestamp ' .
            'WHERE id = ?'
        );
        $this->bindValues($stmt, $entity);
        $this->bindValues(0, $entity->getId());
        $stmt->execute();
        return true;
    }

    /**
     * @param Message $entity
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
        return 'messages';
    }
}
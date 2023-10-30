<?php

namespace App\Model;

use App\Entity\User;
use Lib\Storage\Entity;
use Lib\Storage\Model;
use Lib\Storage\Storage;
use PDO;
use PDOStatement;

class Users implements Model
{
    protected PDO $db;

    public function __construct(Storage $storage)
    {
        $this->db = $storage->getDatabase();
    }

    /**
     * @param PDOStatement $stmt
     * @param User $entity
     *
     * @return void
     */
    public function bindValues(PDOStatement $stmt, Entity $entity): void
    {
        $stmt->bindValue('bundle__name', $entity->getName(), PDO::PARAM_STR);
        $stmt->bindValue('bundle__last_name', $entity->getLastName(), PDO::PARAM_STR);
        $stmt->bindValue('bundle__phone', $entity->getPhone(), PDO::PARAM_STR);
        $stmt->bindValue('bundle__password', $entity->getPassword(), PDO::PARAM_STR);
        $stmt->bindValue('bundle__email', $entity->getEmail(), PDO::PARAM_STR);
    }


    public function get(int $id): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM `{$this->getTable()}` WHERE id=?");
        $stmt->bindValue(0, $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }
        $entity = new User();
        $entity->hydrate($row);
        return $entity;
    }

    /**
     * @param User $entity
     *
     * @return bool
     */
    public function save(Entity $entity): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO `{$this->getTable()}` (" .
                'name, ' .
                'last_name, ' .
                'phone, ' .
                'password, ' .
                'email' .
            ') VALUES (' .
                ':bundle__name, ' .
                ':bundle__last_name, ' .
                ':bundle__phone, ' .
                ':bundle__password, ' .
                ':bundle__email' .
            ')'
        );
        $this->bindValues($stmt, $entity);
        $stmt->execute();
        $id = $this->db->lastInsertId();
        $entity->setId($id);
        return true;
    }

    /**
     * @param Entity $entity
     *
     * @return bool
     */
    public function update(Entity $entity): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE `{$this->getTable()}` SET " .
                'name=:bundle__name, ' .
                'last_name=:bundle__last_name, ' .
                'phone=:bundle__phone, ' .
                'password=:bundle__password, ' .
                'email=:bundle__email ' .
            'WHERE id=?'
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
        return 'users';
    }
}

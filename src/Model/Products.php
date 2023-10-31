<?php

namespace App\Model;

use App\Entity\Product;
use Lib\Storage\Entity;
use Lib\Storage\Model;
use Lib\Storage\Traits\DefaultModel;
use PDO;
use PDOStatement;

class Products{
    use DefaultModel;

    /**
     * @param PDOStatement $stmt
     * @param Product $entity
     *
     * @return void
     */
    public function bindValues(PDOStatement $stmt, Entity $entity): void{
        $stmt->bindValue('bundle__standId', $entity->getStand(), PDO::PARAM_INT);
        $stmt->bindValue('bundle__name', $entity->getName(), PDO::PARAM_STR);
        $stmt->bindValue('bundle__info', $entity->getInfo(), PDO::PARAM_STR);
        $stmt->bindValue('bundle__image', $entity->getImage(), PDO::PARAM_STR);
        $stmt->bindValue('bundle__price', $entity->getPrice(), PDO::PARAM_STR);
    }

    public function all(int $limit = 0, int $offset = 0, int $page = 0): array
    {
        $stmt = $this->getAll($limit, $offset, $page);
        $entities = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $entity = new Product();
            $entities[] = $entity->hydrate($row);
        }

        return $entities;
    }

    public function get(int $id): ?Product
    {
        $row = $this->getByID($id);
        if($row === false){
            return null;
        }
        $entity = new Product();
        $entity->hydrate($row);
    }

    /**
     * @param Product $entity
     *
     * @return bool
     */
    public function save(Entity $entity): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO `{$this->getTable()}` (" .
                'stand_id, ' .
                'name, ' .
                'info, ' .
                'image, ' .
                'price' .
            ') VALUES (' .
                ':bundle__standId, ' .
                ':bundle__name,' .
                ':bundle__info,' .
                ':bundle__image,' .
                ':bundle__price' .
            ')'
        );
        $this->bindValues($stmt, $entity);
        $stmt->execute();
        $id = $this->db->lastInsertId();
        $entity->setId($id);
        return true;
    }

    /**
     * @param Product $entity
     *
     * @return bool
     */
    public function update(Entity $entity): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE `{$this->getTable()}` SET " .
            'stand_id = :bundle__standId, ' .
            'name = :bundle__name, ' .
            'info = :bundle__info, ' .
            'image = :bundle__image, ' .
            'price = :bundle__price ' .
            'WHERE id = ?'
        );
        $this->bindValues($stmt, $entity);
        $this->bindValues(0, $entity->getId());
        $stmt->execute();
        return true;
    }

    /**
     * @param Product $entity
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
        return 'products';
    }
}
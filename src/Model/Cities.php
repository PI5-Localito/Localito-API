<?php

namespace App\Model;

use App\Entity\City;
use Lib\Storage\Entity;
use Lib\Storage\Model;
use Lib\Storage\Traits\DefaultModel;
use PDO;
use PDOStatement;

class Cities implements Model{

    use DefaultModel;

    /**
     * @param PDOStatement $stmt
     * @param City $entity
     *
     * @return void
     */
    public function bindValues(PDOStatement $stmt, Entity $entity): void
    {
        $stmt->bindValue('bundle__countryCode', $entity->getCountry(), PDO::PARAM_STR);
        $stmt->bindValue('bundle__cityName', $entity->getName(), PDO::PARAM_STR);
        $stmt->bindValue('bundle__long', $entity->getLong(), PDO::PARAM_STR);
        $stmt->bindValue('bundle__lat', $entity->getLat(), PDO::PARAM_STR);
    }

    public function all(int $limit = 0, int $offset = 0, int $page = 0): array
    {
        $stmt = $this->getAll($limit, $offset, $page);
        $entities = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $entity = new City();
            $entities[] = $entity->hydrate($row);
        }

        return $entities;
    }

    public function get(int $id): ?City
    {
        $row = $this->getByID($id);
        if($row === false){
            return null;
        }
        $entity = new City();
        $entity->hydrate($row);
    }

    /**
     * @param City $entity
     *
     * @return bool
     */
    public function save(Entity $entity): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO `{$this->getTable()}` (" .
                'country_code,' .
                'city_name,' .
                'longitude,' .
                'latitude' .
            ') VALUES (' .
                ':bundle__countryCode, ' .
                ':bundle__cityName,' .
                ':bundle__long,' .
                ':bundle__lat' .
                ')'
        );
        $this->bindValues($stmt, $entity);
        $stmt->execute();
        $id = $this->db->lastInsertId();
        $entity->setId($id);
        return true;
    }

    /**
     * @param City $entity
     *
     * @return bool
     */
    public function update(Entity $entity): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE `{$this->getTable()}` SET " . 
                'country_code = :bundle__countryCode, ' .
                'city_name = :bundle__cityName, ' .
                'longitude = :bundle__long, ' .
                'latitude = :bundle__lat ' .
            'WHERE id = ?'
        );
        $this->bindValues($stmt, $entity);
        $this->bindValues(0, $entity->getId());
        $stmt->execute();
        return true;
    }

    /**
     * @param City $entity
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
        return 'cities';
    }
}
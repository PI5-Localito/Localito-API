<?php

namespace App\Model;

use App\Entity\Stand;
use Lib\Storage\AbstractModel;

class StandRepo extends AbstractModel
{
    public function getBySeller(int $sid): ?array
    {
        $entity = new $this->entity();
        $entity->sellerId = $sid;

        $data = $this->queryBind(
            "SELECT * FROM {$this->getTable()} WHERE seller_id = :sellerId",
            $entity,
            $entity->includeMapping(['sellerId'])
        );

        return $data ?? null;
    }

    public function allTeaser(int $limit = 0, int $offset = 0, int $page = 0): array
    {
        $query = "SELECT seller_id, tag, stand_name, SUBSTRING(info, 1, 100) as info FROM `{$this->getTable()}`";
        if ($limit !== 0) {
            $offset += $page * $limit;
            $query .= "LIMIT $limit OFFSET $offset";
        }
        return $this->queryBind($query);
    }

    public function getByCategory(int $category, int $limit = 0, int $offset = 0, int $page = 0): array
    {
        $stand = new Stand();
        $stand->category = $category;
        $query = "SELECT seller_id, tag, stand_name FROM `{$this->getTable()}` WHERE category = :category";
        if ($limit !== 0) {
            $offset += $page * $limit;
            $query .= "LIMIT $limit OFFSET $offset";
        }
        return $this->queryBind($query, $stand, $stand->includeMapping(['category']));
    }
}

<?php

namespace App\Model;

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
        $query = "SELECT seller_id, tag, stand_name FROM `{$this->getTable()}`";
        if ($limit !== 0) {
            $offset += $page * $limit;
            $query .= "LIMIT $limit OFFSET $offset";
        }
        return $this->queryBind($query);
    }
}

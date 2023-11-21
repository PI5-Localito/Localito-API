<?php

namespace App\Model;

use Lib\Storage\AbstractModel;

class ProductRepo extends AbstractModel
{
    public function allByStand(int $sid, int $limit = 0, int $offset = 0, int $page = 0): ?array
    {
        $entity = new $this->entity();
        $entity->standId = $sid;

        $query = "SELECT * FROM {$this->getTable()} WHERE stand_id = :standId ";
        if ($limit !== 0) {
            $offset += $page * $limit;
            $query .= "LIMIT $limit OFFSET $offset";
        }

        $data = $this->queryBind($query, $entity, $entity->includeMapping(['standId']));

        return $data ?? null;
    }
}

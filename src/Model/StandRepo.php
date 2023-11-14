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
}

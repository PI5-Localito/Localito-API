<?php

namespace App\Model;

use Lib\Storage\AbstractModel;

class OrderRepo extends AbstractModel
{
    public function getByStand(int $sid): ?array
    {
        $entity = new $this->entity();
        $entity->standId = $sid;

        $data = $this->queryBind(
            "SELECT * FROM {$this->getTable()} WHERE stand_id = :standId",
            $entity,
            $entity->includeMapping(['standId'])
        );

        return $data ?? null;
    }

    public function getByBuyer(int $bid): ?array
    {
        $entity = new $this->entity();
        $entity->buyerId = $bid;

        $data = $this->queryBind(
            "SELECT * FROM {$this->getTable()} WHERE buyer_id = :buyerId",
            $entity,
            $entity->includeMapping(['buyerId'])
        );

        return $data ?? null;
    }
}

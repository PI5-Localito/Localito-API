<?php

namespace App\Model;

use Lib\Storage\AbstractModel;

class MessageRepo extends AbstractModel
{
    public function getByOrder(int $oid): ?array
    {
        $entity = new $this->entity();
        $entity->orderId = $oid;

        $data = $this->queryBind(
            "SELECT * FROM {$this->getTable()} WHERE order_id = :orderId",
            $entity,
            $entity->includeMapping(['orderId'])
        );

        return $data ?? null;
    }
}

<?php

namespace App\Model;

use Lib\Storage\AbstractModel;

class ProductRepo extends AbstractModel
{
    public function getByStand(int $sid): ?Array
    {
        $entity = new $this->entity();
        $entity->standId = $sid;

        $data = $this->executeWrapper(
            "SELECT * FROM {$this->getTable()} WHERE stand_id = :standId",
            $entity,
            $entity->includeMapping(['standId'])
        );

        return $data ?? null;
    }
}

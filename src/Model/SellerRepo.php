<?php

namespace App\Model;

use App\Entity\Seller;
use Lib\Storage\AbstractModel;

class SellerRepo extends AbstractModel
{
    public function getByUser(int $id): false|Seller
    {
        $entity = new Seller();
        $entity->userId = $id;

        $result = $this->executeWrapper(
            "SELECT * FROM {$this->getTable()} WHERE user_id = :userId",
            $entity,
            $entity->includeMapping(['user_id']),
        );

        if ($result === false) {
            return false;
        }

        return $result[0] ?? null;
    }
}

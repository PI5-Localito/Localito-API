<?php

namespace App\Model;

use App\Entity\Seller;
use Lib\Storage\AbstractModel;

class SellerRepo extends AbstractModel
{

    public function getByUser(int $uid): ?Seller
    {
        $entity = new $this->entity();
        $entity->userId = $uid;

        $data = $this->executeWrapper(
            "SELECT * FROM {$this->getTable()} WHERE user_id = :userId",
            $entity,
            $entity->includeMapping(['userId']),
        );

        return $data[0] ?? null;
    }
}

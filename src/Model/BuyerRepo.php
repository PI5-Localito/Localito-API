<?php

namespace App\Model;

use App\Entity\Buyer;
use App\Entity\User;
use Lib\Storage\AbstractModel;

class BuyerRepo extends AbstractModel
{
    public function getByUser(int $id): false|Buyer
    {
        $entity = new Buyer();
        $entity->userId = $id;

        $result = $this->queryBind(
            "SELECT * FROM {$this->getTable()} WHERE user_id = :userId",
            $entity,
            $entity->includeMapping(['userId']),
        );

        if ($result === false) {
            return false;
        }

        return $result[0] ?? null;
    }

    public function buyerChoices(int $uid)
    {
        $user = new User();
        $user->setId($uid);
    }
}

<?php

namespace App\Model;

use App\Entity\Admin;
use Lib\Storage\AbstractModel;

class AdminRepo extends AbstractModel
{
    public function getByEmail(string $email): ?Admin
    {
        $entity = new $this->entity();
        $entity->email = $email;

        $data = $this->queryBind(
            "SELECT * FROM {$this->getTable()} WHERE email = :email",
            $entity,
            $entity->includeMapping(['email']),
        );

        return $data[0] ?? null;
    }

}

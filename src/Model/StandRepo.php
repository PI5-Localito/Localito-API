<?php

namespace App\Model;

use App\Entity\Stand;
use Lib\Storage\AbstractModel;

class StandRepo extends AbstractModel
{
    public function getBySeller(int $sid, int $limit = 0, int $offset = 0, int $page = 0): ?array
    {
        $stand = new Stand();
        $stand->sellerId = $sid;
        $query = "SELECT * FROM `{$this->getTable()}` WHERE seller_id = :sellerId";
        if ($limit !== 0) {
            $offset += $page * $limit;
            $query .= " LIMIT $limit OFFSET $offset";
        }
        $data = $this->queryBind($query, $stand, $stand->includeMapping(['sellerId']));

        return $data ?? null;
    }

    public function allTeaser(int $limit = 0, int $offset = 0, int $page = 0): array
    {
        $query = "SELECT id, seller_id, tag, stand_name, SUBSTRING(info, 1, 100) as info FROM `{$this->getTable()}`";
        if ($limit !== 0) {
            $offset += $page * $limit;
            $query .= "LIMIT $limit OFFSET $offset";
        }
        return $this->queryBind($query);
    }

    public function getByCategory(string $category, int $limit = 0, int $offset = 0, int $page = 0): ?array
    {
        $stand = new Stand();
        $stand->category = $category;
        $query = "SELECT id, seller_id, tag, stand_name, SUBSTRING(info, 1, 100) as info FROM `{$this->getTable()}` WHERE category = :category";
        if ($limit !== 0) {
            $offset += $page * $limit;
            $query .= " LIMIT $limit OFFSET $offset";
        }
        $data = $this->queryBind($query, $stand, $stand->includeMapping(['category']));

        return $data ?? null;
    }
}

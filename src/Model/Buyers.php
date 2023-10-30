<?php

namespace App\Model;

use App\Entity\Buyer;
use App\StorageModelInterface;
use Traversable;

class Buyers implements StorageModelInterface
{
    public const MODEL = 'sellers';

    public function save(Traversable $data): array
    {
    }

    public function delete(): string
    {
    }

    /**
     * Change the state of a buyer in the database
     * @return array
     */
    public function toggleState(Buyer $buyer): array
    {
        return [sprintf('UPDATE `%s` SET `state`=? WHERE `id`=?', static::MODEL), [

        ]];
    }
}

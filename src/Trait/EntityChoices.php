<?php

namespace App\Trait;

use Lib\Storage\AbstractEntity;
use Lib\Storage\AbstractModel;

trait EntityChoices
{
    /**
     * @param AbstractModel $model
     * @param ?callable $cb
     *
     * @return array<string, mixed>
     */
    public function choices(AbstractModel $model, ?callable $cb = null): array
    {
        $cb ??= fn (AbstractEntity $entity) => $entity->id;
        $entities = $model->all();
        $choices = [];

        foreach ($entities as $_ => $entity) {
            $choices[$cb($entity)] = $entity->id;
        }
        return $choices;
    }
}

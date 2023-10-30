<?php

namespace Lib\Storage;

use PDOStatement;

interface Model
{
    public function __construct(Storage $storage);

    /**
     * Get an entity with the corresponding ID
     * @param int $id
     *
     * @return ?Entity
     */
    public function get(int $id): ?Entity;
    public function save(Entity $entity): bool;
    public function update(Entity $entity): bool;
    public function delete(Entity $entity): bool;

    /**
     * Get an entity with the corresponding ID
     * @param int $limit
     * @param int $offset
     * @param int $page
     *
     * @return Entity[]
     */
    public function all(int $limit = 0, int $offset = 0, int $page = 0): array;

    /**
     * Get the table of the entity
     *
     * @return string
     */
    public static function getTable(): string;

    /**
     * Bind the values of the bundle
     * @param PDOStatement $stmt
     * @param Entity $entity
     *
     * @return void
     */
    public function bindValues(PDOStatement $stmt, Entity $entity): void;
}

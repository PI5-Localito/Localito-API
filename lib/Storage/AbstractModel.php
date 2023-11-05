<?php

namespace Lib\Storage;

use Lib\Storage\Annotations\Column;
use Lib\Storage\Annotations\Table;
use Lib\Storage\Traits\ColumnMappings;
use PDOStatement;
use ReflectionClass;
use PDO;

/**
 * Defines the default model implementation
 *
 * @template T of AbstractEntity
 */
abstract class AbstractModel
{
    protected PDO $db;

    /**
     * @param class-string<T> $entity
     */
    protected string $entity;

    public function __construct(Storage $storage, string $entity)
    {
        $this->db = $storage->getDatabase();
        $this->entity = $entity;
    }

    protected function getTable(): ?string
    {
        $refl = new ReflectionClass($this->entity);
        $table = $refl->getAttributes(Table::class)[0] ?? null;
        if (!$table) {
            return null;
        }
        $table = $table->newInstance();
        return $table->getValue();
    }

    /**
     * Bind the values of the bundle
     * @param PDOStatement $stmt
     * @param array<string, Column> $columns
     *
     * @return void
     */
    public function bindValues(PDOStatement $stmt, ColumnMappings $entity, array $columns = []): void
    {
        foreach($columns as $name => $column) {
            $getter = $column->getGetter();
            $value = empty($getter) ? $entity->$name ?? null : $entity->$getter();
            $stmt->bindValue(":$name", $value, $column->getType());
        }
    }

    /**
     * Get an entity with the corresponding ID
     * @param int $id
     *
     * @return ?T
     */
    public function get(int $id): ?AbstractEntity
    {
        $query = "SELECT * FROM {$this->getTable()} WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }
        $entity = new $this->entity();
        $entity->hydrate($row);
        return $entity;
    }

    /**
     * Save the entity
     *
     * @return bool
     */
    public function save(ColumnMappings $entity): bool
    {
        $mappings = $entity->excludeMappigns($entity, ['id']);
        $query = "INSERT INTO {$this->getTable()}";

        $columns = array_map(fn (Column $col) => "`{$col->getColumn()}`", $mappings);
        $query .= '('. implode(',', $columns)  . ')';

        $values = array_map(fn ($n) => ":$n", array_keys($mappings));
        $query .= 'VALUES (' . implode(',', $values) . ')';
        $stmt = $this->db->prepare($query);
        $this->bindValues($stmt, $entity, $mappings);
        return $stmt->execute();
    }

    /**
     * Update the data in the entity
     *
     * @return bool
     */
    public function update(ColumnMappings $entity): bool
    {
        $mappings = $entity->excludeMappigns($entity, ['id']);
        $query = "UPDATE {$this->getTable()} SET";
        $bindings = [];
        foreach($mappings as $name => $column) {
            $bindings[] = "`{$column->getColumn()}` = :$name";
        }
        $query .= ' ' . implode(',', $bindings);
        $query .= ' WHERE `id` = :id';
        $stmt = $this->db->prepare($query);

        $mappings = $entity->getMappings();
        $this->bindValues($stmt, $entity, $mappings);
        return $stmt->execute();
    }

    /**
     * Patch specified fields for an entity
     *
     * @param ColumnMappings $entity
     * @param array<int, string> $fields
     */
    public function patch(ColumnMappings $entity, array $fields): bool
    {
        $mappings = $entity->excludeMappigns($entity, [ 'id', ...$fields ]);
        $query = "UPDATE {$this->getTable()} SET";
        $bindings = [];
        foreach($mappings as $name => $column) {
            $bindings[] = "`{$column->getColumn()}` = :$name";
        }
        $query .= ' ' . implode(',', $bindings);
        $query .= ' WHERE `id` = :id';
        $stmt = $this->db->prepare($query);

        $mappings = $entity->getMappings($entity, $fields);
        $this->bindValues($stmt, $entity, $mappings);
        return $stmt->execute();

    }

    /**
     * Delete the entity
     * @param T $entity
     *
     * @return bool
     */
    public function delete(ColumnMappings $entity): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->getTable()} WHERE `id` = ?");
        $stmt->bindValue(1, $entity->id);
        return $stmt->execute();
    }

    /**
     * Get an entity with the corresponding ID
     * @param int $limit
     * @param int $offset
     * @param int $page
     *
     * @return Entity[]
     */
    public function all(int $limit = 0, int $offset = 0, int $page = 0): array
    {
        $query = "SELECT * FROM `{$this->getTable()}`";
        if ($limit !== 0) {
            $offset += $page * $limit;
            $query .= "LIMIT $limit OFFSET $offset";
        }
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $entities = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $entity = new $this->entity();
            $entity->hydrate($row);
            $entities[] = $entity;
        }
        return $entities;
    }
}

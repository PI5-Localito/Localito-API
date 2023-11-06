<?php

namespace Lib\Storage;

use Lib\Storage\Annotations\Column;
use Lib\Storage\Annotations\Table;
use PDOStatement;
use ReflectionClass;
use PDO;

/**
 * Defines the default model implementation
 *
 */
abstract class AbstractModel
{
    public function __construct(
        protected PDO $db,
        protected string $entity
    ) {
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
     * @return bool
     */
    public function bindValues(PDOStatement $stmt, AbstractEntity $entity, array $columns = []): bool
    {
        foreach($columns as $name => $column) {
            $getter = $column->getGetter();
            $value = empty($getter) ? $entity->$name ?? null : $entity->$getter();
            if (!$stmt->bindValue(":$name", $value, $column->getType())) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get an entity with the corresponding ID
     * @param int $id
     *
     * @return ?AbstractEntity
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
    public function save(AbstractEntity $entity): bool
    {
        $mappings = $entity->excludeMappigns(['id']);
        $query = "INSERT INTO {$this->getTable()}";

        $columns = array_map(fn (Column $col) => "`{$col->getColumn()}`", $mappings);
        $query .= '('. implode(',', $columns)  . ')';

        $values = array_map(fn ($n) => ":$n", array_keys($mappings));
        $query .= 'VALUES (' . implode(',', $values) . ')';
        if($stmt = $this->db->prepare($query)) {
            $this->bindValues($stmt, $entity, $mappings);
            if ($stmt->execute()) {
                $entity->id = $this->db->lastInsertId();
                return true;
            }
        }
        return false;
    }

    /**
     * Update the data in the entity
     *
     * @return bool
     */
    public function update(AbstractEntity $entity): bool
    {
        $mappings = $entity->excludeMappigns(['id']);
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
     * @param AbstractEntity $entity
     * @param array<int, string> $fields
     */
    public function patch(AbstractEntity $entity, array $fields): bool
    {
        $mappings = $entity->excludeMappigns([ 'id', ...$fields ]);
        $query = "UPDATE {$this->getTable()} SET";
        $bindings = [];
        foreach($mappings as $name => $column) {
            $bindings[] = "`{$column->getColumn()}` = :$name";
        }
        $query .= ' ' . implode(',', $bindings);
        $query .= ' WHERE `id` = :id';
        $stmt = $this->db->prepare($query);

        $mappings = $entity->getMappings($fields);
        $this->bindValues($stmt, $entity, $mappings);
        return $stmt->execute();

    }

    /**
     * Delete the entity
     *
     * @return bool
     */
    public function delete(AbstractEntity $entity): bool
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
     * @return AbstractEntity[]
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

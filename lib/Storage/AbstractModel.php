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
    /**
     * @var aray<int, mixed>
     */
    protected array $errors;

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
     * Get the errors raised by the wrapper
     *
     * @return aray<int,mixed>
     */
    final public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Bind the values of the bundle
     * @param PDOStatement $stmt
     * @param array<string, Column> $columns
     *
     * @return bool
     */
    public function bindEntity(PDOStatement $stmt, AbstractEntity $entity, array $columns = []): bool
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
     * Wrapper around the execute pdo statement
     *
     * @param array<string,Column> $mappings
     */
    protected function queryBind(string $query, ?AbstractEntity $entity = null, ?array $mappings = null): bool|array
    {
        $stmt = $this->db->prepare($query);
        if($stmt === false) {
            $this->errors[$this->db->errorCode()] = $this->db->errorInfo();
            return false;
        }

        if ($entity && $mappings) {
            if (!$this->bindEntity($stmt, $entity, $mappings)) {
                $this->errors[$stmt->errorCode()] = $stmt->errorInfo();
                return false;
            }
        }
        if (!$stmt->execute()) {
            $this->errors[$stmt->errorCode()] = $stmt->errorInfo();
            return false;
        }

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if($rows === false) {
            $this->errors[$stmt->errorCode()] = $stmt->errorInfo();
            return false;
        }

        $entities = [];
        foreach($rows as $row) {
            $entity = new $this->entity();
            $entity->hydrate($row);
            $entities[] = $entity;
        }
        return $entities;
    }

    /**
     * Get an entity with the corresponding ID
     * @param int $id
     *
     * @return ?AbstractEntity
     */
    public function get(int $id): ?AbstractEntity
    {
        $entity = new $this->entity();
        $entity->id = $id;

        $data = $this->queryBind(
            "SELECT * FROM {$this->getTable()} WHERE id = :id",
            $entity,
            $entity->includeMapping(['id']),
        );

        return $data[0] ?? null;
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

        if ($this->queryBind($query, $entity, $mappings) !== false) {
            $entity->id = $this->db->lastInsertId();
            return true;
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
        $mappings = $entity->getMappings();

        return $this->queryBind($query, $entity, $mappings) !== false;
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

        $mappings = $entity->getMappings($fields);
        return $this->queryBind($query, $entity, $mappings) !== false;
    }

    /**
     * Delete the entity
     *
     * @return bool
     */
    public function delete(AbstractEntity $entity): bool
    {
        return $this->queryBind(
            "DELETE FROM {$this->getTable()} WHERE `id` = :id",
            $entity,
            $entity->includeMapping(['id']),
        ) !== false;
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
        return $this->queryBind($query);
    }
}

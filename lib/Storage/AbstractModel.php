<?php

namespace Lib\Storage;

use Lib\Storage\Annotations\Column;
use Lib\Storage\Annotations\Table;
use Lib\Storage\Traits\ColumnHydrate;
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
    use ColumnHydrate;
    use ColumnMappings;

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
     * @param AbstractEntity $entity
     * @param array<string, Column> $columns
     *
     * @return void
     */
    public function bindValues(PDOStatement $stmt, AbstractEntity $entity, array $columns = []): void
    {
        foreach($columns as $name => $column) {
            $getter = $column->getGetter();
            $value = empty($getter) ? $entity->$name : $entity->$getter();
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
        $this->hydrate($entity, $row);
        return $entity;
    }

    /**
     * Save the entity
     * @param T $entity
     *
     * @return bool
     */
    public function save(AbstractEntity $entity): bool
    {
        $mappings = $this->excludeMappigns($entity, ['id']);
        $col_filter = fn ($map) => "`{$map[0]->getCloumn()}`";
        $query = "INSERT INTO {$this->getTable()}";
        $query .= '('. implode(',', array_map($mappings, $col_filter)) . ')';
        $query .= 'VALUES (' . implode(',', array_map(array_keys($mappings), fn ($n) => ":$n")) . ')';
        $stmt = $this->db->prepare($query);
        $this->bindValues($stmt, $entity, $mappings);
        return $stmt->execute();
    }

    /**
     * Update the data in the entity
     * @param T $entity
     *
     * @return bool
     */
    public function update(AbstractEntity $entity): bool
    {
        $mappings = $this->excludeMappigns($entity, ['id']);
        $query = "UPDATE {$this->getTable()} SET";
        $bindings = [];
        foreach($mappings as $name => $column) {
            $bindings .= "`{$column->getColumn()}` = :$name";
        }
        $query .= ' ' . implode(',', $bindings);
        $query .= ' WHERE `id` = ?';
        $stmt = $this->db->prepare($query);
        $this->bindValues($stmt, $entity, $mappings);
        $stmt->bindValue(1, $entity->id);
        return $stmt->execute();
    }

    /**
     * Delete the entity
     * @param T $entity
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
     * @return Entity[]
     */
    public function all(int $limit = 0, int $offset = 0, int $page = 0): array
    {
        //     $entities = [];
        //     while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        //         $entity = new User();
        //         $entity->hydrate($row);
        //         $entities[] = $entity;
        //     }
    }
}

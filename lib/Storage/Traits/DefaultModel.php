<?php

namespace Lib\Storage\Traits;

use Lib\Storage\Storage;
use PDO;
use PDOStatement;

trait DefaultModel 
{
    private PDO $db;

    abstract static public function getTable(): string;

	public function __construct(Storage $storage)
    {
    	$this->db = $storage->getDatabase();
    }


	protected function getAll(int $limit = 0, int $offset = 0, int $page = 0): PDOStatement
	{
		$query = "SELECT * FROM `{$this->getTable()}`";
        if ($limit !== 0){
            $offset += $page * $limit;
            $query .= "LIMIT $limit OFFSET $offset";
        }
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt;
	}	

	protected function getByID(int $id): false|array
	{
		$stmt = $this->db->prepare("SELECT * FROM `{$this->getTable()}` WHERE id=?");
        $stmt->bindValue(0, $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
	}
}
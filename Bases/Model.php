<?php

namespace Core\Bases;

use Core\Application;
use Core\Database\Connectable;
use Core\Database\Connection;
use Core\Database\Database;
use Core\Exceptions\DatabaseException;
use Core\Utils\Logger;

abstract class Model {

    public Database $database;
    public Connectable $connection;
    protected string|false $table = false;

    public function __construct()
    {
        if (!$this->table) {
            Logger::error(__CLASS__ . ' table not specified!');
            throw new DatabaseException(__CLASS__ . ' table not specified!');
        }
        $this->connection = Connection::getInstance();
        $this->database = new Database($this->connection);
        $this->database->table($this->table);
    }

    /**
     * @param $where
     * @param $class
     * @return mixed
     */
    public function findToObject($where, $class): mixed {
        $columns = implode("AND ", array_map(fn($key) => "$key = :$key", array_keys($where)));
        $statement = $this->connection->getConnection()->prepare("SELECT * FROM $this->table WHERE $columns;");
        foreach ($where as $key => $value) {
            $statement->bindValue(":$key", $value);
        }
        $statement->execute();
        return $statement->fetchObject($class);
    }

}
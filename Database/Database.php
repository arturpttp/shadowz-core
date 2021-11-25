<?php

namespace Core\Database;

use Core\System\Arrays;
use Core\Utils\Logger;
use PDO;
use PDOException;

class Database
{

    private Connectable $connectable;
    private PDO $connection;
    private \PDOStatement $statement;
    private false|string $table = false;
    private string $query;
    private int $count;
    private string|false $error = false;
    private mixed $fetch;
    private bool $result = true;

    /**
     * @param Connectable $connectable
     */
    public function __construct(Connectable $connectable)
    {
        $this->connectable = $connectable;
        if ($connectable->isConnected())
            $this->connection = $this->connectable->getConnection();
    }

    public function table($table): Database
    {
        $this->table = $table;
        return $this;
    }

    public function query(string|false $query = false, $params = [], $a = false): Database
    {
        try {
            if ($query !== false)
                $this->query = $query;
            $this->statement = $this->connection->prepare($query);
            if ($a) {
                $x = 1;
                foreach ($params as $param) {
                    $this->statement->bindValue($x, $param);
                    $x++;
                }
            }
            if ($this->result = $this->statement->execute(!$a ? $params : null)) {
                $this->count = $this->statement->rowCount();
                $this->error = false;
            } else {
                $this->error = "some error on query {$query}";
            }
        } catch (PDOException $exception) {
            $this->error = "some error on query {$exception->getMessage()}";
        }
        if ($this->error)
            $this->result = false;
        return $this;
    }

    public function find(array $data): Database {
        $columns = $this->prepareArrayToQuery(array_keys($data), true);
        $this->query("SELECT * FROM $this->table WHERE $columns", array_values($data), true);
        return $this;
    }

    public function delete(array $data): Database {
        $columns = $this->prepareArrayToQuery(array_keys($data), true);
        $this->query("DELETE FROM $this->table WHERE $columns", array_values($data), true);
        return $this;
    }

    public function insert(array $data) : Database {
        $columns = $this->prepareArray($data);
        $values = $this->prepareArray($data, true);
        $this->query("INSERT INTO $this->table($columns) VALUES($values);", array_values($data), true);
        return $this;
    }

    public function update($data, $where): Database
    {
        $_data = $this->prepareArrayToQuery($data);
        $_where = $this->prepareArrayToQuery($where, true);
        $this->query("UPDATE $this->table SET $_data WHERE $_where", array_values(Arrays::merge($data, $where)), true);
        return $this;
    }

    private function prepareArray(array $array, $toInterrogation = false): string {
        $string = "";
        $i = 1;
        foreach ($array as $item) {
            $string .= resumeIf($toInterrogation, '?', $item);
            if ($i < count($array))
                $string .= ', ';
            $i++;
        }
        return $string;
    }

    private function prepareArrayToQuery(array $array, $and = false): string {
        $string = "";
        $i = 1;
        foreach ($array as $item => $itemValue) {
            $string .= "$item = ?";
            if ($i < count($array))
                $string .= resumeIf($and, ' AND ', ', ');
            $i++;
        }
        return $string;
    }

    public function drop(): Database
    {
        if ($this->table != false) {
            $this->query("DROP TABLE {$this->table}");
        }
        return $this;
    }

    public function dropColumn($column)
    {
        $this->query("DROP COLUMN $column");
        return $this;

    }

    public function all(): Database
    {
        $this->query("SELECT * FROM {$this->table}");

        return $this;
    }

    public function contains(): bool
    {

        $args = func_get_args();
        if (is_array($args[0])) {
            $column = $args[0][0];
            $value = $args[0][1];
        } else {
            $column = $args[0];
            $value = $args[1];
        }

        if(is_null($column) || is_null($value))
            Logger::error('Database::contains, you must provide ');

        $this->query("SELECT * FROM $this->table WHERE $column = ?;", [$value], true);

        return $this->count > 0;
    }

    public function count(): int
    {
        $this->count = $this->statement->rowCount();
        return $this->count;
    }

    public function fetch(int $fetchMode = PDO::FETCH_ASSOC): mixed
    {
        if ($this->count() > 1)
            $this->fetch = $this->statement->fetchAll($fetchMode);
        else
            $this->fetch[] = $this->statement->fetch($fetchMode);
        return $this->fetch;
    }

    public function fetchAll(int $fetchMode = PDO::FETCH_ASSOC): mixed
    {
        $this->fetch = $this->statement->fetchAll($fetchMode);
        return $this->fetch;
    }

    public function first($fetchMethod = PDO::FETCH_ASSOC)
    {
        $results = $this->fetch($fetchMethod);
        return is_array($results) ? $results[0] : $results;
    }

    public function getError(): bool|string
    {
        return $this->error;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function isResult(): bool
    {
        return $this->result;
    }
    public function getStatement(): \PDOStatement
    {
        return $this->statement;
    }


}
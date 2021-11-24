<?php

namespace Core\Database;

class DB extends Connection
{

    private static DB $instance;

    private string $query, $table;
    private \PDOStatement|false $statement;
    private bool $result;


    public function query($query = null, $binds = [], $bv = false): DB
    {
        if (empty($this->table))
            throw new \RuntimeException("Unknown table");
        if ($query !== null) {
            $this->query = $query;
        }
        $this->statement = $this->getConnection()->prepare($this->query);
        if ($bv) {
            $x = 1;
            foreach ($binds as $bind) {
                $this->statement->bindValue($x, $bind);
                $x++;
            }
            $this->result = $this->statement->execute();
        } else
            $this->result = $this->statement->execute($binds);
        return $this;
    }

    public function table($table): DB {
        $this->table = $table;
        return $this;
    }

    public static function instance(): DB
    {
        if (empty(self::$instance) || is_null(self::$instance) || !self::$instance instanceof DB)
            self::$instance = new self;
        return self::$instance;
    }


}
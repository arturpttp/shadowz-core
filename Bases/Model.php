<?php

namespace Core\Bases;

use Core\Application;
use Core\Database\Connection;
use Core\Database\Database;
use Core\Utils\Logger;

abstract class Model {

    public Database $database;
    protected string|false $table = false;

    public function __construct()
    {
        if (!$this->table) {
            Logger::error(__CLASS__ . ' table not specified!');
            die;
        }
        $this->database = new Database(Connection::getInstance());
        $this->database->table($this->table);
    }

}
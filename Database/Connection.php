<?php

namespace Core\Database;

use Core\Application;
use Core\System\Config;
use Core\Utils\Logger;
use PDO;
use PDOException;

class Connection implements Connectable
{

    public array $config;
    public PDO|null $connection;

    public string $driver;
    public string $host;
    public string $database;
    public array $options;

    private static Connectable $instance;

    public function __construct($autoConnect = false)
    {
        $this->config = Config::get('database');
        if ($autoConnect)
            $this->connect();
        $this->getInstance();
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function isConnected(): bool
    {
        return !((is_null($this->connection) or !$this->connection or !($this->connection instanceof PDO)));
    }

    public function connect()
    {
        $this->driver = $this->config['driver'];
        $this->options = $this->config['options'];
        $this->host = $this->config[$this->driver]['host'];
        $this->database = $this->config[$this->driver]['database'];
        if ($this->driver == "mysql") {
            $user = $this->config[$this->driver]['user'];
            $password = $this->config[$this->driver]['password'];
            try {
                $this->connection = new PDO("mysql:host={$this->host};dbname={$this->database}", $user, $password);
                foreach ($this->options as $key => $value) $this->connection->setAttribute($key, $value);

            } catch (PDOException $exception) {
                $this->onError($exception);
            }
        } else if ($this->driver == "sqlite") {
            die("sqlite is in WIP");
        } else {
            die("Database driver is invalid");
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function onError(PDOException $error)
    {
        $this->connection = null;
        Logger::error('An error occurred on trying to connect to database! Error: {error} on line: {line}', ['message' => $error->getMessage(), 'line' => $error->getLine()]);
    }

    public static function getInstance(): Connectable
    {
        if (self::$instance === null or !(self::$instance instanceof Application))
            self::$instance = new self();
        return self::$instance;
    }

    public function execute(string $query, ?array $params = null, $binding = false): \PDOStatement
    {
        $statement = is_null($params) ? $this->connection->query($query) : $this->connection->prepare($query);
        if (!is_null($params))
            if ($binding) {
                $x = 1;
                foreach ($params as $param => $value) {
                    $statement->bindParam($x, $value);
                    $x++;
                }
            }
        $statement->execute($binding ? null : $params);
        return $statement;
    }

}
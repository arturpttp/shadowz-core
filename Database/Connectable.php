<?php

namespace Core\Database;

use PDO;
use PDOException;

interface Connectable
{

    public function getConfig(): array;
    public function isConnected(): bool;
    public function connect();
    public function getConnection(): PDO;
    public function onError(PDOException $error);

}
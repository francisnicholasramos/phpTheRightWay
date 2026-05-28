<?php 

namespace App\Models;
use Core\Database;
use PDO;

abstract class Model {
    protected PDO $pdo;
    protected string $table;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

     /* pdo cannot be serialized, so exclude it when caching
     /  model objects in redis
     */
    public function __sleep(): array {
        return array_diff(array_keys(get_object_vars($this)), ['pdo']);
    }

    // restore DB connection after unserialization
    public function __wakeup(): void {
        $this->pdo = Database::getInstance();
    }

    public function getConnection(): PDO {
        return $this->pdo;
    }
}

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

    public function getConnection(): PDO {
        return $this->pdo;
    }
}

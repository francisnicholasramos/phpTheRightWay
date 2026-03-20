<?php

namespace Core;

use PDO;

class Database {
    private static ?PDO $instance = null;

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            self::$instance = require __DIR__ . '/../config/database.php';
        }

        return self::$instance;
    }

    // prevents new instantiation and cloning
    private function __construct() {}
    private function __clone() {}
}

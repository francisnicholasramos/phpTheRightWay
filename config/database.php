<?php

require __DIR__. '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

try {
    $dbhost = $_ENV['DB_HOST'] ?? '';
    $dbname = $_ENV['DB_DATABASE'] ?? '';
    $dbuser = $_ENV['DB_USERNAME'] ?? '';
    $dbpass = $_ENV['DB_PASSWORD'] ?? '';
    $ssl    = $_ENV['SSLMODE'] ?? null;

    $dsn = "pgsql:host=$dbhost;dbname=$dbname";

    if (!empty($ssl)) {
        $dsn .= ";sslmode=$ssl";
    }

    $connect = new PDO($dsn, $dbuser, $dbpass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    return $connect;

} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    throw $e;
}

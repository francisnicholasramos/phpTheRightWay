<?php 

$pdo = require __DIR__ . '/config/database.php';

$pdo->exec('CREATE EXTENSION IF NOT EXISTS "pgcrypto";');


$sql = <<<SQL
    CREATE TABLE IF NOT EXISTS "users" (
        "id"         TEXT        NOT NULL DEFAULT gen_random_uuid(),
        "username"   TEXT        NOT NULL,
        "email"      TEXT        NOT NULL,
        "password"   TEXT        NOT NULL,
        "avatar"     TEXT,
        "bio"        TEXT,
        "created_at" TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,

        CONSTRAINT "users_pkey"          PRIMARY KEY ("id"),
        CONSTRAINT "users_id_key"        UNIQUE ("id"),
        CONSTRAINT "users_username_key"  UNIQUE ("username"),
        CONSTRAINT "users_email_key"     UNIQUE ("email")
    );
SQL;

try {
    $pdo->exec($sql);
    echo "Migration completed successfully.\n";
} catch (PDOException $e) {
    die("[error] Migration failed: " . $e->getMessage() . "\n");
}

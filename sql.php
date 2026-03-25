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

    CREATE TYPE "visibility_type" AS ENUM ('public', 'friends', 'private');
    CREATE TABLE IF NOT EXISTS "posts" (
        "id"        TEXT        NOT NULL DEFAULT gen_random_uuid(),
        "content"   TEXT        NOT NULL,
        "user_id"   TEXT        NOT NULL,
        "created_at" TIMESTAMP(3)   NOT NULL DEFAULT CURRENT_TIMESTAMP,
        "visibility" visibility_type NOT NULL DEFAULT 'public',

        FOREIGN KEY ("user_id")    REFERENCES "users"("id"),
        
        CONSTRAINT "posts_pkey"         PRIMARY KEY ("id")
    );

    CREATE TABLE IF NOT EXISTS "comments" (
        "id"        TEXT        NOT NULL DEFAULT gen_random_uuid(),
        "content"   TEXT        NOT NULL,
        "user_id"   TEXT        NOT NULL,
        "created_at" TIMESTAMP(3)   NOT NULL DEFAULT CURRENT_TIMESTAMP,
        "post_id"   TEXT        NOT NULL,
        
        FOREIGN KEY ("post_id")    REFERENCES "posts"("id") ON DELETE CASCADE ON UPDATE CASCADE,

        CONSTRAINT "comments_pkey"         PRIMARY KEY ("id")
    );

    CREATE TABLE IF NOT EXISTS "likes" (
        "id"        TEXT        NOT NULL DEFAULT gen_random_uuid(),
        "user_id"   TEXT        NOT NULL,
        "post_id"   TEXT        NOT NULL,

        FOREIGN KEY ("user_id")    REFERENCES "users"("id") ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY ("post_id")    REFERENCES "posts"("id") ON DELETE CASCADE ON UPDATE CASCADE,

        CONSTRAINT "likes_pkey"    PRIMARY KEY ("id")
    );
SQL;

try {
    $pdo->exec($sql);
    echo "Migration completed successfully.\n";
} catch (PDOException $e) {
    die("[error] Migration failed: " . $e->getMessage() . "\n");
}

<?php 

$pdo = require __DIR__ . '/config/database.php';

$pdo->exec('CREATE EXTENSION IF NOT EXISTS "pgcrypto";');


$sql = <<<SQL
    DO $$ BEGIN
        CREATE TYPE "visibility_type" AS ENUM ('public', 'friends', 'private');
    EXCEPTION 
        WHEN duplicate_object THEN NULL;
    END $$;

    DO $$ BEGIN
        CREATE TYPE "like_entity_type" AS ENUM ('post', 'comment');
    EXCEPTION 
        WHEN duplicate_object THEN NULL;
    END $$;

    DO $$ BEGIN
        CREATE TYPE "notification_type" AS ENUM ('like', 'comment', 'friend_request', 'poke');
    EXCEPTION 
        WHEN duplicate_object THEN NULL;
    END $$;

    CREATE TABLE IF NOT EXISTS "users" (
        "id"         UUID        NOT NULL DEFAULT gen_random_uuid(),
        "username"   TEXT        NOT NULL,
        "email"      TEXT        NOT NULL,
        "password"   TEXT        NOT NULL,
        "avatar"     TEXT,
        "bio"        TEXT,
        "created_at" TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,

        CONSTRAINT "users_pkey"          PRIMARY KEY ("id"),
        CONSTRAINT "users_username_key"  UNIQUE ("username"),
        CONSTRAINT "users_email_key"     UNIQUE ("email")
    );

    CREATE TABLE IF NOT EXISTS "posts" (
        "id"        UUID        NOT NULL DEFAULT gen_random_uuid(),
        "content"   TEXT        NOT NULL,
        "user_id"   UUID        NOT NULL,
        "created_at" TIMESTAMP(3)   NOT NULL DEFAULT CURRENT_TIMESTAMP,
        "updated_at" TIMESTAMP(3)   NOT NULL DEFAULT CURRENT_TIMESTAMP,
        "visibility" visibility_type NOT NULL DEFAULT 'public',

        FOREIGN KEY ("user_id")    REFERENCES "users"("id"),
        
        CONSTRAINT "posts_pkey"         PRIMARY KEY ("id")
    );

    CREATE INDEX IF NOT EXISTS "posts_user_id_idx" ON "posts"("user_id");
    CREATE INDEX IF NOT EXISTS "posts_created_at_idx" ON "posts"("created_at" DESC);

    CREATE TABLE IF NOT EXISTS "comments" (
        "id"        UUID        NOT NULL DEFAULT gen_random_uuid(),
        "content"   TEXT        NOT NULL,
        "user_id"   UUID        NOT NULL,
        "post_id"   UUID        NOT NULL,
        "parent_id"   UUID,
        "created_at" TIMESTAMP(3)   NOT NULL DEFAULT CURRENT_TIMESTAMP,
        
        FOREIGN KEY ("user_id")    REFERENCES "users"("id") ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY ("post_id")    REFERENCES "posts"("id") ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY ("parent_id")  REFERENCES "comments"("id") ON DELETE CASCADE ON UPDATE CASCADE,

        CONSTRAINT "comments_pkey"         PRIMARY KEY ("id")
    );

    CREATE INDEX IF NOT EXISTS "comments_user_id_idx" ON "comments"("user_id");
    CREATE INDEX IF NOT EXISTS "comments_post_id_idx" ON "comments"("post_id");
    CREATE INDEX IF NOT EXISTS "comments_parent_id_idx" ON "comments"("parent_id");

    CREATE TABLE IF NOT EXISTS "likes" (
        "user_id"     UUID        NOT NULL,
        "entity_id"   UUID        NOT NULL,
        "entity_type"  like_entity_type  NOT NULL,

        FOREIGN KEY ("user_id")    REFERENCES "users"("id") ON DELETE CASCADE ON UPDATE CASCADE,
        
        CONSTRAINT "likes_pkey" PRIMARY KEY ("user_id", "entity_id", "entity_type")
    );
    
    CREATE INDEX IF NOT EXISTS "likes_entity_id_type_idx" ON "likes"("entity_id", "entity_type");
    
    CREATE TABLE IF NOT EXISTS "notifications" (
        "id"            UUID                NOT NULL DEFAULT gen_random_uuid(),
        "user_id"       UUID                NOT NULL,
        "from_user_id"  UUID                NOT NULL,
        "entity_id"     UUID                NOT NULL,
        "entity_type"   notification_type   NOT NULL,
        "is_read"       BOOLEAN             NOT NULL DEFAULT FALSE,
        "created_at" TIMESTAMP(3)   NOT NULL DEFAULT CURRENT_TIMESTAMP,
        
        FOREIGN KEY ("user_id")          REFERENCES   "users"("id") ON DELETE CASCADE,
        FOREIGN KEY ("from_user_id")     REFERENCES   "users"("id") ON DELETE CASCADE,

        CONSTRAINT "notifications_pkey" PRIMARY KEY ("id")
    );

    CREATE INDEX IF NOT EXISTS "notifications_user_id_idx" ON "notifications"("user_id");
    CREATE INDEX IF NOT EXISTS "notifications_from_user_id_idx" ON "notifications"("from_user_id");

SQL;

try {
    $pdo->exec($sql);
    echo "Migration completed successfully.\n";
} catch (PDOException $e) {
    die("[error] Migration failed: " . $e->getMessage() . "\n");
}

<?php

return [
    'driver'   => 'pgsql',
    'host'     => getenv('DB_HOST') ?: '127.0.0.1',
    'port'     => getenv('DB_PORT') ?: '5432',
    'database' => getenv('DB_DATABASE') ?: '',
    'username' => getenv('DB_USERNAME') ?: '',
    'password' => getenv('DB_PASSWORD') ?: '',
    'charset'  => 'utf8',
];

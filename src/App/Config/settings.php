<?php
return [
    // View settings
    'view' => [
        'template_path' => APP_DIR . '/../templates',
        'twig' => [
            'cache' => ROOT_DIR . '/storage/cache/twig',
            'debug' => true,
            'auto_reload' => true,
        ],
    ],

    // monolog settings
    'logger' => [
        'name' => 'app',
        'path' => ROOT_DIR . '/storage/log/app.log',
    ],
    // db settings
    'database' => [
        'driver' => getenv('DB_DRIVER'),
        'dbname' => ROOT_DIR . getenv('DB_NAME'),
        'user' => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD'),
        'encoding' => getenv('DB_ENCODING'),
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_BOTH,
        ],
        'initCommands' => [
            'PRAGMA journal_mode=MEMORY;',
            'PRAGMA busy_timeout=2000;'
        ],
    ]
];

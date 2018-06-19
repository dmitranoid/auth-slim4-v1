<?php
/**
 * Created by PhpStorm.
 * User: svt3
 * Date: 19.06.2018
 * Time: 8:33
 */

namespace Tests\Unit\TestHelpers;


trait PdoUseTrait
{

    function getRealPdoDb()
    {
        $dbname = __DIR__ . '/../../../'.'data/data.sqlite3';
        $db = [
            'driver' => 'sqlite',
            'dbname' => $dbname,
            'user' => '',
            'password' => '',
            'encoding' => 'utf-8',
            'options' => [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_BOTH,
            ],
            'initCommands' => [
                'PRAGMA journal_mode=MEMORY;',
                'PRAGMA busy_timeout=2000;'
            ],
        ];
        $dsn = "{$db['driver']}:{$db['dbname']}";
        try {
            $pdo = new \PDO($dsn, $db['user'], $db['password']);
        } catch (\PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
            throw $e;
        }
        foreach ($db['initCommands'] ?? [] as $command) {
            $pdo->exec($command);
        }
        return $pdo;
    }

}
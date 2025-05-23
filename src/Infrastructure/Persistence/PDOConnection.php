<?php
namespace Infrastructure\Persistence;

use PDO;

class PDOConnection
{
    public static function make(array $config): PDO
    {
        return new PDO(
            "mysql:host={$config['host']};dbname={$config['name']};charset=utf8mb4",
            $config['user'],
            $config['pass']
        );
    }
}

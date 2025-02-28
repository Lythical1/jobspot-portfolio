<?php

class Database
{
    private static $connection;

    public static function connectDb()
    {
        if (!isset(self::$connection)) {
            $config = self::getConfig();
            try {
                self::$connection = new PDO(
                    "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4",
                    $config['username'],
                    $config['password'],
                    [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"]
                );
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }

    private static function getConfig()
    {
        return [
            'host' => 'mysql',
            'database' => 'jobspot',
            'username' => 'bit_academy',
            'password' => 'bit_academy'
        ];
    }
}

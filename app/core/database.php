<?php

class Database
{
    public static function connectDb()
    {
        try {
            $pdo = new PDO(
                "mysql:host=mysql;dbname=jobspot",
                "bit_academy",
                "bit_academy"
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            echo "Please contact the administrator. <br>";
            die("Connection failed: " . $e->getMessage());
        }
    }
}

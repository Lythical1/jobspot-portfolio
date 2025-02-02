<?php

class Database
{
    public function __construct()
    {
        $this->connectDb();
    }
    private static function connectDb()
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
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getJobs()
    {
        $pdo = $this->connectDb();
        $stmt = $pdo->prepare("SELECT * FROM jobs");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getCompanies()
    {
        $pdo = $this->connectDb();
        $stmt = $pdo->prepare("SELECT * FROM companies");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

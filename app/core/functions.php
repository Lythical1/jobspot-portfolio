<?php

class Database
{
    public function __construct()
    {
        
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
            echo "Please contact the administrator. <br>";
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getJobs()
    {
        $pdo = $this->connectDb();
        $stmt = $pdo->prepare("
            SELECT jobs.*, companies.name as company 
            FROM jobs 
            LEFT JOIN companies ON jobs.company_id = companies.id
        ");
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

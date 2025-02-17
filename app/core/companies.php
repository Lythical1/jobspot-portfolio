<?php

require_once 'database.php';

class CompanyRepository
{
    public function getCompanies()
    {
        $pdo = Database::connectDb();
        $stmt = $pdo->prepare("SELECT * FROM companies");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

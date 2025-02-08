<?php

require_once 'database.php';

class User
{
    public function __construct()
    {
    }

    public function getUser($id)
    {
        $pdo = Database::connectDb();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function createUser($name, $email, $password)
    {
        $pdo = Database::connectDb();
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
    }

    public function updateUser($id, $name, $email, $password)
    {
        $pdo = Database::connectDb();
        $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
    }

    public function deleteUser($id)
    {
        $pdo = Database::connectDb();
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}
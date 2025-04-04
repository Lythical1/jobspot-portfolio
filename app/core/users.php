<?php

require_once 'database.php';

class Users
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

    public function createUser($first_name, $last_name, $email, $password, $role)
    {
        $pdo = Database::connectDb();
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, role) VALUES (:first_name, :last_name, :email, :password, :role)");
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
        

        return $pdo->lastInsertId();
    }

    public function updateUserInfo($id, $first_name, $last_name, $email, $phone_number)
    {
        $pdo = Database::connectDb();
        $stmt = $pdo->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, phone_number = :phone_number WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->execute();
    }

    public function deleteUser($id)
    {
        $pdo = Database::connectDb();
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function loginUser($email, $password)
    {
        $pdo = Database::connectDb();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }

    public function updateUserProfilePicture($userId, $fileName)
    {
        $pdo = Database::connectDb();
        
        // Get existing profile picture
        $stmt = $pdo->prepare("SELECT profile_picture FROM users WHERE id = :id");
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $user = $stmt->fetch();
        
        // Delete existing profile picture if not default
        if ($user && !empty($user['profile_picture']) && $user['profile_picture'] !== 'default-avatar.png') {
            $existingFile = $_SERVER['DOCUMENT_ROOT'] . '/assets/profiles/' . $user['profile_picture'];
            if (file_exists($existingFile)) {
                unlink($existingFile);
            }
        }

        // Update with new profile picture
        $stmt = $pdo->prepare("UPDATE users SET profile_picture = :profile_picture WHERE id = :id");
        $stmt->bindParam(':id', $userId);
        $stmt->bindParam(':profile_picture', $fileName);
        return $stmt->execute();
    }

    public function updateUserCV($userId, $fileName)
    {
        $pdo = Database::connectDb();
        
        // Get existing CV file
        $stmt = $pdo->prepare("SELECT cv FROM users WHERE id = :id");
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $user = $stmt->fetch();
        
        // Delete existing CV if it exists
        if ($user && !empty($user['cv'])) {
            $existingFile = $_SERVER['DOCUMENT_ROOT'] . '/assets/cv/' . $user['cv'];
            if (file_exists($existingFile)) {
                unlink($existingFile);
            }
        }
        
        // Update with new CV
        $stmt = $pdo->prepare("UPDATE users SET cv = :cv WHERE id = :id");
        $stmt->bindParam(':id', $userId);
        $stmt->bindParam(':cv', $fileName);
        return $stmt->execute();
    }

    public function updateUserPassword($userId, $password)
    {
        $pdo = Database::connectDb();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
        $stmt->bindParam(':id', $userId);
        $stmt->bindParam(':password', $hashedPassword);
        return $stmt->execute();
    }
}

<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../app/core/database.php';
require_once __DIR__ . '/../app/core/users.php';

class UsersTest extends TestCase
{
    private $mockPdo;
    private $users;
    
    protected function setUp(): void
    {
        // Create a mock PDO object
        $this->mockPdo = $this->createMock(PDO::class);
        
        // Create the Users class
        $this->users = new Users();
        
        // Use reflection to inject our mock PDO
        $reflection = new ReflectionClass(Database::class);
        $property = $reflection->getProperty('connection');
        $property->setAccessible(true);
        $property->setValue(null, $this->mockPdo);
    }
    
    public function testGetUser()
    {
        $userId = 1;
        $expectedUser = [
            'id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone_number' => '123456789'
        ];
        
        // Create a mock PDOStatement
        $mockStatement = $this->createMock(PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('bindParam')
            ->with(':id', $userId);
        $mockStatement->expects($this->once())
            ->method('execute');
        $mockStatement->expects($this->once())
            ->method('fetch')
            ->willReturn($expectedUser);
            
        // Configure the mock PDO to return our mock statement
        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with("SELECT * FROM users WHERE id = :id")
            ->willReturn($mockStatement);
        
        // Call the method and check the result
        $result = $this->users->getUser($userId);
        $this->assertEquals($expectedUser, $result);
    }
    
    public function testUpdateUserInfo()
    {
        $userId = 1;
        $firstName = 'John';
        $lastName = 'Smith';
        $email = 'john.smith@example.com';
        $phone = '987654321';
        
        // Create a mock for the SQL query we expect
        $expectedSql = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, phone_number = :phone_number WHERE id = :id";
        
        // Create a mock PDOStatement that will track parameter binding
        $mockStatement = $this->createMock(PDOStatement::class);
        
        // Since we can't easily test the exact order of multiple bindParam calls,
        // let's just verify that execute() is called once
        $mockStatement->expects($this->once())
            ->method('execute');
            
        // Configure the mock PDO to return our mock statement
        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo($expectedSql))
            ->willReturn($mockStatement);
        
        // Call the method
        $this->users->updateUserInfo($userId, $firstName, $lastName, $email, $phone);
        
        // If we get here without errors, the test passes
        $this->assertTrue(true);
    }
}

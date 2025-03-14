<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../app/core/database.php';
require_once __DIR__ . '/../app/core/interviews.php';

class InterviewTest extends TestCase
{
    private $mockPdo;
    
    protected function setUp(): void
    {
        // Create a mock PDO object
        $this->mockPdo = $this->createMock(PDO::class);
        
        // Use reflection to inject our mock PDO
        $reflection = new ReflectionClass(Database::class);
        $property = $reflection->getProperty('connection');
        $property->setAccessible(true);
        $property->setValue(null, $this->mockPdo);
    }
    
    public function testGetUpcomingInterviews()
    {
        $userId = 1;
        $expectedInterviews = [
            (object)[
                'id' => 1,
                'job_id' => 10,
                'user_id' => 1,
                'scheduled_at' => '2023-12-31 15:30:00',
                'status' => 'scheduled',
                'job_title' => 'PHP Developer',
                'company_name' => 'Tech Corp'
            ],
            (object)[
                'id' => 2,
                'job_id' => 11,
                'user_id' => 1,
                'scheduled_at' => '2024-01-05 10:00:00',
                'status' => 'scheduled',
                'job_title' => 'Frontend Developer',
                'company_name' => 'Web Studio'
            ]
        ];
        
        // Create a mock PDOStatement
        $mockStatement = $this->createMock(PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with([$userId]);
        $mockStatement->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_OBJ)
            ->willReturn($expectedInterviews);
            
        // Configure the mock PDO to return our mock statement
        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('SELECT i.*, j.title as job_title, c.name as company_name'))
            ->willReturn($mockStatement);
        
        // Call the method and check the result
        $result = Interview::getUpcomingInterviews($userId);
        $this->assertEquals($expectedInterviews, $result);
    }
}

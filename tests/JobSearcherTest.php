<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../app/core/database.php';
require_once __DIR__ . '/../app/core/job_searchers.php';

class JobSearcherTest extends TestCase
{
    private $mockPdo;
    private $jobSearcher;
    
    protected function setUp(): void
    {
        // Create a mock PDO object
        $this->mockPdo = $this->createMock(PDO::class);
        
        // Use reflection to inject our mock PDO into Database::connection
        $reflection = new ReflectionClass(Database::class);
        $property = $reflection->getProperty('connection');
        $property->setAccessible(true);
        $property->setValue(null, $this->mockPdo);
        
        // Create a JobSearcher instance
        $this->jobSearcher = new JobSearcher();
    }
    
    public function testGetSearchers()
    {
        // Expected data to be returned from the database
        $expectedSearchers = [
            [
                'id' => 1,
                'title' => 'JavaScript Developer',
                'category_id' => 3
            ]
        ];
        
        // Create a mock PDOStatement
        $mockStatement = $this->createMock(PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute');
        $mockStatement->expects($this->once())
            ->method('fetchAll')
            ->willReturn($expectedSearchers);
        
        // Configure the mock PDO to return our mock statement
        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->willReturn($mockStatement);
        
        // Call the method being tested
        $result = $this->jobSearcher->getSearchers();
        
        // Verify the results
        $this->assertEquals($expectedSearchers, $result);
    }
    
    public function testFilterSearchersWithMultipleFilters()
    {
        // Multiple category IDs for filtering
        $categoryFilters = [2, 3];
        
        // Expected data after filtering
        $expectedFilteredSearchers = [
            [
                'id' => 2,
                'title' => 'PHP Engineer',
                'user_id' => 15,
                'salary_range' => '45000-65000',
                'category_id' => 2
            ],
            [
                'id' => 3,
                'title' => 'Frontend Developer',
                'user_id' => 18,
                'salary_range' => '40000-60000',
                'category_id' => 3
            ]
        ];
        
        // Create a mock PDOStatement
        $mockStatement = $this->createMock(PDOStatement::class);
        
        // Instead of using withConsecutive, we'll simplify by
        // just verifying execute is called and return the expected results
        $mockStatement->expects($this->exactly(2))
            ->method('bindValue');
        
        $mockStatement->expects($this->once())
            ->method('execute');
            
        $mockStatement->expects($this->once())
            ->method('fetchAll')
            ->willReturn($expectedFilteredSearchers);
            
        // Configure the mock PDO to return our mock statement
        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with($this->callback(function($sql) {
                // Verify the SQL contains the correct placeholders
                return strpos($sql, 'category_id IN (?,?)') !== false;
            }))
            ->willReturn($mockStatement);
        
        // Call the method being tested
        $result = $this->jobSearcher->filterSearchers($categoryFilters);
        
        // Verify the results
        $this->assertEquals($expectedFilteredSearchers, $result);
        $this->assertCount(2, $result);
    }
    
    public function testFilterSearchersWithSingleFilter()
    {
        // Single category ID for filtering
        $categoryFilter = 2;
        
        // Expected data for a single filter
        $expectedFilteredSearchers = [
            [
                'id' => 2,
                'title' => 'PHP Engineer',
                'user_id' => 15,
                'salary_range' => '45000-65000',
                'category_id' => 2
            ]
        ];
        
        // Create a mock PDOStatement
        $mockStatement = $this->createMock(PDOStatement::class);
        
        $mockStatement->expects($this->once())
            ->method('execute');
            
        $mockStatement->expects($this->once())
            ->method('fetchAll')
            ->willReturn($expectedFilteredSearchers);
            
        // Configure the mock PDO to return our mock statement
        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('SELECT * FROM job_searchers WHERE category_id IN (?)'))
            ->willReturn($mockStatement);
        
        // Call the method being tested
        $result = $this->jobSearcher->filterSearchers($categoryFilter);
        
        // Verify the results
        $this->assertEquals($expectedFilteredSearchers, $result);
        $this->assertCount(1, $result);
    }
}

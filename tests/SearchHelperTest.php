<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../app/core/algoSearch.php';

class SearchHelperTest extends TestCase
{
    public function testNormalizeString()
    {
        // Test with regular string
        $this->assertEquals('hello world', SearchHelper::normalizeString('Hello World!'));
        
        // Test with special characters
        $this->assertEquals('specialchars', SearchHelper::normalizeString('Special-Chars!@#'));
        
        // Test with null
        $this->assertEquals('', SearchHelper::normalizeString(null));
        
        // Test with empty string
        $this->assertEquals('', SearchHelper::normalizeString(''));
        
        // Test with whitespace
        $this->assertEquals('trim spaces', SearchHelper::normalizeString('  Trim Spaces  '));
    }
    
    public function testAreSimilar()
    {
        // Test exact match
        $this->assertTrue(SearchHelper::areSimilar('developer', 'developer'));
        
        // Test case insensitivity
        $this->assertTrue(SearchHelper::areSimilar('Developer', 'developer'));
        
        // Test with small differences (within threshold)
        $this->assertTrue(SearchHelper::areSimilar('developer', 'developr'));
        
        // Test with differences beyond threshold
        $this->assertFalse(SearchHelper::areSimilar('developer', 'programmer'));
        
        // Test with custom threshold
        $this->assertTrue(SearchHelper::areSimilar('programmer', 'programr', 2));
        $this->assertFalse(SearchHelper::areSimilar('programmer', 'programr', 1));
    }
    
    public function testFormatSalary()
    {
        // Test single salary
        $this->assertEquals('€ 50000', SearchHelper::formatSalary('50000'));
        
        // Test salary range
        $this->assertEquals('€ 40000  -  € 60000', SearchHelper::formatSalary('40000-60000'));
        
        // Test with EUR prefix
        $this->assertEquals('€ 50000', SearchHelper::formatSalary('EUR50000'));
        
        // Test with empty salary
        $this->assertEquals('Salary not specified', SearchHelper::formatSalary(''));
        
        // Test with null
        $this->assertEquals('Salary not specified', SearchHelper::formatSalary(null));
    }
}

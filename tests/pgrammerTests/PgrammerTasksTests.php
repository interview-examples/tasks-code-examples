<?php

namespace PHPInterviewTasks\Tests\pgrammerTests;

use PHPUnit\Framework\TestCase;
use PHPInterviewTasks\pgrammer\PgrammerTasks;

class PgrammerTasksTests extends TestCase
{
    public function testGenerateSimilarWordSlug()
    {
        $this->assertEquals(
            'php-the-right-way-for-starters',
            PgrammerTasks::generateSimilarWordSlug(
                'PHP The-right Way for Beginnings',
                ['beginners' => 'starters'],
                0.7
            )
        );
        $this->assertEquals(
            'how-to-succeed-in-software-engineering-interviews-at-faang-companies',
            PgrammerTasks::generateSimilarWordSlug(
                'How to Secure in Software Engineering Interviews at FAANG Companies',
                ['secure' => 'succeed']
            )
        );
        $this->assertEquals(
            'the-quick-crown-fox-jumps-over-the-lazy-dog',
            PgrammerTasks::generateSimilarWordSlug(
                'Phe Quick Crown Fax Jumps Ovar Phe Lazy Dig',
                [
                    'phe' => 'the',
                    'fax' => 'fox',
                    'ovar' => 'over',
                    'dig' => 'dog'
                ]
            )
        );
    }

    public function testCountOccurrences()
    {
        // Test case 1
        $input = [1, 2, 2, 3, 4, 4, 4];
        $expected = [1 => 1, 2 => 2, 3 => 1, 4 => 3];
        $this->assertEquals($expected, PgrammerTasks::countOccurrences($input));

        // Test case 2
        $input = [5, 5, 5, 5, 5];
        $expected = [5 => 5];
        $this->assertEquals($expected, PgrammerTasks::countOccurrences($input));

        // Test case 3
        $input = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $expected = [1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1, 6 => 1, 7 => 1, 8 => 1, 9 => 1, 10 => 1];
        $this->assertEquals($expected, PgrammerTasks::countOccurrences($input));
    }
}
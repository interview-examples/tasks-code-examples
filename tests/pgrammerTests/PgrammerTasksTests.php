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
}
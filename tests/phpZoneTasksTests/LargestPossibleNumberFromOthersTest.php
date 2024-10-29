<?php
use PHPUnit\Framework\TestCase;
use PHPInterviewTasks\phpZoneTasks\PhpZoneTasks;
require_once(__DIR__."/../../src/phpZoneTasks/LargestPossibleNumberFromOthers.php");

class LargestPossibleNumberFromOthersTest extends TestCase {
    public function testGetLargestNumberFromOthers()
    {
        $this->assertEquals("9958142211100", getLargestNumberFromOthers("100 95 9 2 42 11 81"));
        $this->assertEquals(getLargestNumberFromOthers("100 95 9 2 42 11 81"),
            PhpZoneTasks::getLargestNumberFromOthers("100 95 9 2 42 11 81")
        );
    }
}

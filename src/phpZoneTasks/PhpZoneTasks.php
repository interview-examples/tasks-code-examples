<?php

namespace PHPInterviewTasks\phpZoneTasks;

class PhpZoneTasks
{
    static public function getLargestNumberFromOthers (string $line): string
    {
        $tmp_array = explode(" ", $line);

        usort($tmp_array, function($p1, $p2) {
            $order_ltr = $p1 . $p2;
            $order_rtl = $p2 . $p1;
            return $order_rtl <=> $order_ltr;
        });
        return implode('', $tmp_array);
    }
}
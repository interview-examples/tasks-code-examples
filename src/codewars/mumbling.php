<?php
function accum($s)
{
    $result = [];

    foreach (str_split($s) as $index => $char) {
        $char = strtolower($char);  // because lower case letter using more offten, minimaze number of calls function strtolower()
        $result[] = strtoupper($char) . str_repeat($char, $index);
    }

    return implode('-', $result);
}
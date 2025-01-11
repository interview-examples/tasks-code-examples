<?php

function productFib($prod)
{
    $a = 0;
    $b = 1;
    while ($a * $b < $prod) {
        $temp = $b;
        $b = $a + $b;
        $a = $temp;
    }
    return [$a, $b, $a * $b === $prod];
}

var_dump(productFib(714)); echo PHP_EOL; // test passed
var_dump(productFib(800)); echo PHP_EOL; // test passed
var_dump(productFib(4895)); echo PHP_EOL; // test passed
var_dump(productFib(5895)); echo PHP_EOL;   // test failed - result should be [89, 144, false]????
var_dump(productFib(74049690)); echo PHP_EOL; // test passed

<?php
function isPerfectSquare($x): bool
{
    $s = (int)sqrt($x);
    return $s * $s === $x;
}

function isFibonacci($n)
{
    // Следствие из формулы Бине - является ли 5*n^2 + 4 или 5*n^2 - 4 полным квадратом
    return isPerfectSquare(5 * $n * $n + 4) || isPerfectSquare(5 * $n * $n - 4);
}

function productFib($prod)
{
    $result = [0, 0, false];
    for ($i = 2; $i * $i <= $prod; $i++) {
        if ($prod % $i === 0) {
            $pair = [$i, $prod / $i];
            if ($pair[0] < $pair[1]) {
                if (isFibonacci($pair[0]) && isFibonacci($pair[1])) {
                    $result = [$pair[0], $pair[1], true];
                    break;
                } else {
                    $result = [$pair[0], $pair[1], false];
                }
            }
        }
    }
    return $result;
}

var_dump(productFib(714)); echo PHP_EOL; // test passed
var_dump(productFib(800)); echo PHP_EOL; // test passed
var_dump(productFib(4895)); echo PHP_EOL; // test passed
var_dump(productFib(5895)); echo PHP_EOL;   // test failed - result should be [89, 144, false]????
var_dump(productFib(74049690)); echo PHP_EOL; // test passed

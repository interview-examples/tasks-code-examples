<?php
function toCamelCase($str)
{
    $str = preg_split('/[\s\-_]+/', $str, -1, PREG_SPLIT_NO_EMPTY);
    return array_shift($str) . implode('', array_map('ucfirst', $str));
}

echo(toCamelCase("the_stealth_warrior")); echo PHP_EOL;
echo(toCamelCase("The_stealth-Warrior")); echo PHP_EOL;
echo(toCamelCase("the Stealth warrior")); echo PHP_EOL;
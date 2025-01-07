<?php
function toCamelCase($str)
{
    $words = preg_split('/[\s\-_]+/', $str);
    $first_word = $words[0];
    return $first_word . implode('', array_map('ucfirst', array_slice($words, 1)));
}

echo(toCamelCase("the_stealth_warrior")); echo PHP_EOL;
echo(toCamelCase("The_stealth-Warrior")); echo PHP_EOL;
echo(toCamelCase("the Stealth warrior")); echo PHP_EOL;
<?php
function getCount(string $str): int {
    preg_match_all('/[aeiou]/', $str, $out);
    return count($out[0]);
}

echo getCount("aeiou"); echo PHP_EOL;
echo getCount("aeiyou"); echo PHP_EOL;
echo getCount("oueai"); echo PHP_EOL;
echo getCount("bcdfghjklmnpqrstvwxz y"); echo PHP_EOL;
echo getCount(""); echo PHP_EOL;
echo getCount("abracadabra"); echo PHP_EOL;
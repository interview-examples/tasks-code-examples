<?php
function getCount(string $str): int {
    /*preg_match_all('/[aeiou]/', $str, $out);
    return count($out[0]);*/

    /*
     * Использование модификатора i: Этот модификатор делает поиск нечувствительным к регистру, что позволяет находить как строчные, так и заглавные гласные без дополнительных усилий.
     * Возврат значения функцией: preg_match_all напрямую возвращает количество найденных соответствий, что делает код очень кратким.
     */
    return preg_match_all('/[aeiou]/i', $str, $matches);
}

echo getCount("aeiou"); echo PHP_EOL;
echo getCount("aeiyou"); echo PHP_EOL;
echo getCount("oueai"); echo PHP_EOL;
echo getCount("bcdfghjklmnpqrstvwxz y"); echo PHP_EOL;
echo getCount(""); echo PHP_EOL;
echo getCount("abracadabra"); echo PHP_EOL;
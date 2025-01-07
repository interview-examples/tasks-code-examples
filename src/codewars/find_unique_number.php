<?php
function find_uniq($arr)
{
    $counts = [];

    foreach ($arr as $num) {
        $key = (string)$num; // Преобразуем в строку для использования в качестве ключа
        if (!isset($counts[$key])) {
            $counts[$key] = 0;
        }
        $counts[$key]++;
    }

    foreach ($counts as $key => $value) {
        if ($value === 1) {
            return (float)$key == (int)$key ? (int)$key : (float)$key; // Преобразуем обратно в соответствующий тип
        }
    }
}

echo find_uniq([1, 2, 1, 3, 2]); echo PHP_EOL;
echo find_uniq([1, 5, 1, 3, 5]); echo PHP_EOL;
echo find_uniq([1, 1, 1, 2, 1, 1]); echo PHP_EOL;
echo find_uniq([0, 0, 0.55, 0, 0]); echo PHP_EOL;
echo find_uniq([0, 1, 0]); echo PHP_EOL;


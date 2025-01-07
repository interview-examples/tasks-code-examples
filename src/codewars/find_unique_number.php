<?php
function find_uniq($arr)
{
    $frequency = array_count_values(array_map('strval',$arr));

    foreach ($frequency as $key => $value) {
        if ($value === 1) {
            return (+$key);
        }
    }
}

echo find_uniq([1, 2, 1, 3, 2]); echo PHP_EOL;
echo find_uniq([1, 5, 1, 3, 5]); echo PHP_EOL;
echo find_uniq([1, 1, 1, 2, 1, 1]); echo PHP_EOL;
echo find_uniq([0, 0, 0.55, 0, 0]); echo PHP_EOL;
echo find_uniq([0, 1, 0]); echo PHP_EOL;


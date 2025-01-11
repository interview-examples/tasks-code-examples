<?php
function tower_builder(int $rows_number): array
{
    $brick = '*';
    $space = ' ';
    $tower = [];
    $count_bricks = 1;
    $max_line = $rows_number * 2 - 1;
    for ($i = 0; $i < $rows_number; $i++) {
        $fill_space = str_repeat($space, ($max_line - 1)/2 - $i);
        $tower[$i] = $fill_space . str_repeat($brick, $count_bricks) . $fill_space;
        $count_bricks +=2;
    }

    return $tower;
}
print_r(tower_builder(6)); echo PHP_EOL;
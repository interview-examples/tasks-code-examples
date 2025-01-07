<?php
function open_or_senior(array $data): array {
    $res = [];
    foreach ($data as $player) {
        $res[] = $player[0] >= 55 && $player[1] > 7 ? 'Senior' : 'Open';
    };

    return $res;
}

print_r(open_or_senior([[45, 12], [55, 21], [19, -2], [104, 20]])); echo PHP_EOL;
print_r(open_or_senior( [[3, 12], [55, 1], [91, -2], [54, 23]])); echo PHP_EOL;
print_r(open_or_senior([[59, 12], [55, -1], [12, -2], [12, 12]])); echo PHP_EOL;

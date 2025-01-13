<?php
function secondsConvertToHuman(int $seconds): string
{
    $result = "";
    $hour_duration = 60 * 60;
    $day_duration = $hour_duration * 24;
    $year_duration = $day_duration * 365;

    $year = intdiv($seconds, $year_duration);
    $day = intdiv(($seconds % $year_duration), $day_duration);
    $hour = intdiv(($seconds % $day_duration), $hour_duration);
    $minute = intdiv(($seconds % $hour_duration), 60);
    $second = $seconds % 60;

    return formatDuration($year, $day, $hour, $minute, $second);
}

function formatDuration(int $year, int $day, int $hour, int $minute, int $second): string
{
    $parts = [
        ['value' => $year, 'singular' => 'year', 'plural' => 'years'],
        ['value' => $day, 'singular' => 'day', 'plural' => 'days'],
        ['value' => $hour, 'singular' => 'hour', 'plural' => 'hours'],
        ['value' => $minute, 'singular' => 'minute', 'plural' => 'minutes'],
        ['value' => $second, 'singular' => 'second', 'plural' => 'seconds'],
    ];

    $resultParts = [];
    foreach ($parts as $part) {
        if ($part['value'] > 0) {
            $resultParts[] = $part['value'] . ' ' . ($part['value'] > 1 ? $part['plural'] : $part['singular']);
        }
    }

    $last = array_pop($resultParts);
    $resultParts ? implode(', ', $resultParts) . ' and ' . $last : $last;

    return $resultParts ? implode(', ', $resultParts) . ' and ' . $last : $last;
}

echo (secondsConvertToHuman(52)); echo PHP_EOL;
echo (secondsConvertToHuman(60)); echo PHP_EOL;
echo (secondsConvertToHuman(62)); echo PHP_EOL;
echo (secondsConvertToHuman(3600)); echo PHP_EOL;
echo (secondsConvertToHuman(3660)); echo PHP_EOL;
echo (secondsConvertToHuman(51180)); echo PHP_EOL;
echo (secondsConvertToHuman(54662)); echo PHP_EOL;
echo (secondsConvertToHuman(86400)); echo PHP_EOL;
echo (secondsConvertToHuman(1234672)); echo PHP_EOL;
echo (secondsConvertToHuman(43234678)); echo PHP_EOL;
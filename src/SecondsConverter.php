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

    if ($year > 0) {
        $result .= $year . " year" . ($year > 1 ? "s" : "") . " ";
    }
    if ($day > 0) {
        $result .= ($year !== 0 && $hour === 0 && $minute === 0 && $second === 0 ? "and " : "") . $day . " day" . ($day > 1 ? "s" : "") . " ";
    }
    if ($hour > 0) {
        $result .= (($year !== 0 || $day !==0) && $minute === 0 && $second === 0 ? "and " : "") . $hour . " hour" . ($hour > 1 ? "s" : "") . " ";
    }
    if ($minute > 0) {
        $result .= (($year !== 0 || $hour !== 0) && $second === 0 ? "and " : "") . $minute . " minute" . ($minute > 1 ? "s" : "") . " ";
    }
    if ($second > 0) {
        $result .= ($year !== 0 ||$hour !== 0 || $minute !== 0 ? "and " : "") . $second . " second" . ($second > 1 ? "s" : "");
    }

    return $result;
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
<?php
function RomanNumeralsDecoder (string $roman)
{
    $codeAddTable = [
        'M' => 1000,
        'D' => 500,
        'C' => 100,
        'L' => 50,
        'X' => 10,
        'V' => 5,
        'I' => 1,
    ];
    $codeSubTable = [
        'CM' => 900,
        'CD' => 400,
        'XC' => 90,
        'XL' => 40,
        'IX' => 9,
        'IV' => 4,
    ];
    $number = 0;

    foreach ($codeSubTable as $key => $value) {
        if (strpos($roman, $key) !== false) {
            $number += $value;
            $roman = str_replace($key, '', $roman);
        }
    }
    foreach ($codeAddTable as $key => $value) {
        while (strpos($roman, $key) !== false) {
            $count = 0;
            $roman = str_replace($key, '', $roman, $count);
            $number += $count * $value;
        }
    }

    return $number;
}

echo(RomanNumeralsDecoder("MMXVII")); echo PHP_EOL;
echo(RomanNumeralsDecoder("MCCCXXXVII")); echo PHP_EOL;
echo(RomanNumeralsDecoder("MCMXC")); echo PHP_EOL;
/*echo(RomanNumeralsDecoder("")); echo PHP_EOL;
echo(RomanNumeralsDecoder("")); echo PHP_EOL;*/

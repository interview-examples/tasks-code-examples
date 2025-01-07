<?php
function reverseWords($str) {
    return implode(' ', array_map('strrev', explode(' ', $str)));
}

echo reverseWords("The quick brown fox jumps over the lazy dog"); echo PHP_EOL;
echo reverseWords("  double  spaced  words  ");; echo PHP_EOL;

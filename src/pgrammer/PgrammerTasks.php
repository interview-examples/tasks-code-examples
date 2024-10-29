<?php

namespace PHPInterviewTasks\pgrammer;

class PgrammerTasks
{
    /**
     * This function should accept a string input (the title of a blog post), a dictionary of similar word replacements and a threshold of similarity as input, and output a URL-friendly slug that replaces words in the title that are deemed 'similar' enough to words in the dictionary by the provided threshold. The threshold is a float number between 0 and 1 where 1 means the words must be exactly the same and 0 means any word can be replaced.
     *
     * The way to determine word similarity will be using Levenshtein’s distance algorithm, you can use built-in PHP function for this. The function should also fulfill the following requirements:
     * - Convert the title to lowercase.
     * - Replace spaces in the title with the '-' delimiter.
     * - Remove all non-alphanumeric characters excluding the delimiter from the title.
     *
     * @param string $title
     * @param array $dictionary
     * @param float $threshold_similarity
     * @return string
     */
    public static function generateSimilarWordSlug(
        string $title,
        array $dictionary,
        float $threshold_similarity = 0.8): string
    {
        $title = strtolower(trim($title, " -\n\r\t\v\x00"));
        $title = preg_replace('/[^a-z0-9-\s]+/', '', $title);
        $title = preg_replace('/[\s_]+/', '-', $title);

        $arr_title = explode("-", $title);
        $res_similar = [];

        foreach ($arr_title as $value) {
            if (!isset($res_similar[$value])) {
                $res_similar[$value] = self::applyLevenshtain($value, $dictionary, $threshold_similarity);
            }
        }
        $out = array_map(function ($fragment) use ($res_similar) {
            return $res_similar[$fragment];
        }, $arr_title);

        return implode("-", $out);
    }

    /**
     * Apply Levenshtain algorithm to every item of the Dictionary
     * @param string $src
     * @param array $dict
     * @param float $threshold_similarity
     * @return string
     */
    private static function applyLevenshtain(
        string $src,
        array $dict,
        float $threshold_similarity): string
    {
        $res = $src;
        $max_similarity = $threshold_similarity;
        if (array_key_exists($src, $dict)) {
            $res = $dict[$src];
        } else {
            foreach ($dict as $key => $value) {
                $levenshtein_distance = levenshtein($src, $key);
                $max_length = max(strlen($src), strlen($key));
                $similarity = 1 - ($levenshtein_distance / $max_length);

                if ($similarity >= $max_similarity) {
                    $res = $value;
                    $max_similarity = $similarity;
                }
            }
        }

        return $res;
    }

    /**
     * The function takes an array of integers as an argument and
     * returns a dictionary or associative array where the key is an integer from
     * an input array and the value is the count of occurrences of that number in the array
     *
     * @param array $arr
     * @return array|null
     */
    public static function countOccurrences(array $arr): ?array
    {
        $result = [];
        foreach ($arr as $value) {
            if (!isset($result[$value])) {
                $result[$value] = 1;
            } else {
                $result[$value]++;
            }
        }

        return $result;
    }
}
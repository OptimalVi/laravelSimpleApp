<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Expression;

class DBHelper
{

    /**
     * Split string to words and convert for ilike any() query
     *
     * @param string $string
     * @return string
     */
    public static function stringToLikeAnyWords(string $string, bool $asRaw = false): string|Expression
    {
        $string = static::arrayToSqlArrayOrSingle(
            static::stringToLikeWords($string),
            'any'
        );
        return $asRaw ? DB::raw($string) : $string;
    }

    /**
     * Make from array Sql any array[val1, val2]
     *
     * @param array $values
     * @param bool $asRaw
     * @return string
     */
    public static function arrayToSqlAnyArrayOrSingle(array $values, bool $asRaw = false): string
    {
        return static::arrayToSqlArrayOrSingle(
            static::prepareArrayWithStrings($values),
            'any'
        );
    }

    /**
     * Make from array Sql raw any array[val1, val2]
     *
     * @param array $values
     * @return Expression
     */
    public static function arrayToSqlRawAnyArrayOrSingle(array $values): Expression
    {
        return DB::raw(static::arrayToSqlAnyArrayOrSingle($values));
    }

    /**
     * Split string to words and convert for ilike all() query
     *
     * @param string $string
     * @return string
     */
    public static function stringToLikeAllWords(string $string): string
    {
        return static::arrayToSqlArrayOrSingle(
            static::stringToLikeWords($string),
            'all'
        );
    }

    /**
     * Make from array Sql any array[val1, val2]
     *
     * @param array $values
     * @return string
     */
    public static function arrayToSqlAllArrayOrSingle(array $values): string
    {
        return static::arrayToSqlArrayOrSingle(
            static::prepareArrayWithStrings($values),
            'all'
        );
    }

    /**
     * Make sql array, can be with wrapper(any, all...)
     *
     * @param array $values
     * @param string|null $wrapper
     * @return string any? (array[value1, value2])
     */
    public static function arrayToSqlArrayOrSingle(array $values, string $wrapper = null): string
    {
        if (count($values) > 1) {
            $sql = ' array[' . implode(',', $values) . '] ';
            return $wrapper ? " $wrapper($sql) " : $sql;
        }

        return current($values);
    }

    /**
     * @param array $values
     * @param string $operator
     * @param $condition
     * @param $conditionValue
     * @return string
     */
    public static function arrayToSqlSingleCondition(
        array $values,
        string $operator,
        $condition,
        $conditionValue
    ): string {
        if (count($values)) {
            $result = '';
            foreach ($values as $key => $value) {
                $result = $key > 0 ? " $result $condition " : $result;
                $result .= " $value $operator $conditionValue ";
            }

            return $result;
        }

        return current($values);
    }

    /**
     * Split string to array with words
     *
     * @param $string
     * @return array
     */
    public static function stringToLikeWords($string): array
    {
        $words = [];
        if (!($substrs = explode(' ', $string))) {
            return [];
        }
        foreach ($substrs as $word) {
            $word = trim($word);
            $words[] = DB::connection()->getPdo()->quote("%$word%");
        }

        return $words;
    }

    /**
     * @param array $values
     * @return array
     */
    public static function prepareArrayWithStrings(array $values): array
    {
        return array_map(static function ($value) {
            if (is_string($value)) {
                return DB::connection()->getPdo()->quote($value);
            }
            return $value;
        }, $values);
    }
}

<?php

namespace w3lifer\phpHelper;

use DateTime;
use DateTimeZone;

class PhpHelper
{
    /**
     * Returns received array where keys are prefixed with specified prefix.
     * @param array  $array
     * @param string $prefix
     * @param bool   $recursively
     * @return array
     * @see https://stackoverflow.com/a/2608166/4223982
     */
    public static function add_prefix_to_array_keys(
        array $array,
        string $prefix,
        bool $recursively = true
    ) : array
    {
        $newArray = [];
        foreach ($array as $key => $value) {
            if ($recursively && is_array($value)) {
                $newArray[$prefix . $key] =
                    self::add_prefix_to_array_keys($value, $prefix);
                continue;
            }
            $newArray[$prefix . $key] = $value;
        }
        return $newArray;
    }

    /**
     * Returns received array where keys are suffixed with specified suffix.
     * @param array  $array
     * @param string $suffix
     * @param bool   $recursively
     * @return array
     * @see https://stackoverflow.com/a/2608166/4223982
     */
    public static function add_suffix_to_array_keys(
        array $array,
        string $suffix,
        bool $recursively = true
    ) : array
    {
        $newArray = [];
        foreach ($array as $key => $value) {
            if ($recursively && is_array($value)) {
                $newArray[$key . $suffix] =
                    self::add_suffix_to_array_keys($value, $suffix);
                continue;
            }
            $newArray[$key . $suffix] = $value;
        }
        return $newArray;
    }

    /**
     * Basic access authentication.
     * @param array $credentials An array whose keys are logins and values are
     *                           passwords.
     * @return bool
     */
    public static function auth(array $credentials) : bool
    {
        $validated =
            isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) &&
            array_key_exists($_SERVER['PHP_AUTH_USER'], $credentials) &&
            $credentials[$_SERVER['PHP_AUTH_USER']] === $_SERVER['PHP_AUTH_PW'];
        if (!$validated) {
            header('HTTP/1.1 401 Unauthorized');
            header('WWW-Authenticate: Basic');
            return false;
        }
        return true;
    }

    /**
     * Clears all cookies.
     * @return bool
     * @see https://stackoverflow.com/a/2310591/4223982
     */
    public static function clear_all_cookies() : bool
    {
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach ($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time() - 1000);
                setcookie($name, '', time() - 1000, '/');
            }
            return true;
        }
        return false;
    }

    /**
     * Converts CSV string to array.
     * Example of CSV string:
     * ``` csv
     * First name,Last name
     * John,Doe
     * "Richard", "Roe"
     * ```
     * Result with `$removeFirstLine` as `false`:
     * ``` php
     * [
     *   0 => [
     *     0 => 'First name',
     *     1 => 'Last name',
     *   ],
     *   1 => [
     *     0 => 'John',
     *     1 => 'Doe',
     *   ],
     *   2 => [
     *     0 => 'Richard',
     *     1 => 'Roe',
     *   ],
     * ]
     * ```
     * Result with `$removeFirstLine` as `true`:
     * ``` php
     * [
     *   0 => [
     *     0 => 'John',
     *     1 => 'Doe',
     *   ],
     *   1 => [
     *     0 => 'Richard',
     *     1 => 'Roe',
     *   ],
     * ]
     * ```
     * @param string $csvString
     * @param bool   $removeFirstLine
     * @return array
     */
    public static function csv_string_to_array(
        string $csvString,
        bool $removeFirstLine = false
    ) : array
    {
        $trimmedCsvString = trim($csvString);
        $explodedCsvString = explode(PHP_EOL, $trimmedCsvString);
        if ($removeFirstLine) {
            unset($explodedCsvString[0]);
        }
        $result = [];
        foreach ($explodedCsvString as $csvLine) {
            $csvLine = trim($csvLine);
            $result[] = str_getcsv($csvLine);
        }
        return $result;
    }

    /**
     * Returns an array of dates between two dates.
     * For example, if input data will be '1969-12-31' and '1970-01-02'
     * (or '12/31/1969' and '01/02/1970'), then result will be the following:
     * ``` php
     * [
     *   0 => '1969-12-31',
     *   1 => '1970-01-01',
     *   2 => '1970-01-02',
     * ]
     * ```
     * @param string $startDate
     * @param string $endDate
     * @param string $format
     * @return array
     */
    public static function get_dates_between_dates(
        string $startDate,
        string $endDate,
        string $format = 'Y-m-d'
    ) : array
    {
        $dates[] = date($format, strtotime($startDate));
        $dateDiff = (new DateTime($startDate))->diff(new DateTime($endDate));
        for ($i = 1; $i <= $dateDiff->days; $i++) {
            $dates[] = date($format, strtotime($dates[$i - 1] . '+1 day'));
        }
        return $dates;
    }

    /**
     * Adds timezone offset to timestamp and returns new timestamp.
     * @param int    $timestamp
     * @param string $timeZone
     * @return int
     */
    public static function add_timezone_offset_to_timestamp(
        int $timestamp,
        string $timeZone
    ) : int
    {
        return $timestamp + self::get_timezone_offset($timeZone);
    }

    /**
     * Returns timezone offset from the current time zone.
     * @param string $timeZone
     * @return int Timezone offset in seconds.
     */
    public static function get_timezone_offset(string $timeZone) : int
    {
        return (new DateTime(null, new DateTimeZone($timeZone)))->getOffset();
    }

    /**
     * Checks if request is AJAX.
     * @return bool
     */
    public static function is_ajax() : bool
    {
        return
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    /**
     * Makes a string's first character uppercase.
     * @param string $string
     * @return string
     * @see https://php.net/ucfirst#57298
     */
    public static function mb_ucfirst(string $string) : string
    {
        $firstChar = mb_strtoupper(mb_substr($string, 0, 1));
        return $firstChar . mb_substr($string, 1);
    }

    /**
     * Returns a parsable string representation of a received array.
     * Example of the incoming array:
     * ```
     * [
     *   'a' => 1,
     *   'b' => [
     *     'c' => 3,
     *   ],
     * ]
     * ```
     * Example of the returned string:
     * ```
     * array (
     *   'a' => 1,
     *   'b' => array (
     *     'c' => 3,
     *   ),
     * )
     * ```
     * @param $array
     * @return string
     */
    public static function pretty_var_export_soft(array $array) : string
    {
        $arrayAsString = var_export($array, true);
        $arrayAsString =
            preg_replace(
                '= \=\> \R {2,}array \(=', ' => array (',
                $arrayAsString
            );
        return $arrayAsString;
    }

    /**
     * Returns a parsable string representation of a received array.
     * Example of the incoming array:
     * ```
     * [
     *   'a' => 1,
     *   'b' => [
     *     'c' => 3,
     *   ],
     * ]
     * ```
     * Example of the returned string:
     * ```
     * [
     *   'a' => 1,
     *   'b' => [
     *     'c' => 3,
     *   ],
     * ]
     * ```
     * @param $array
     * @return string
     */
    public static function pretty_var_export_hard(array $array) : string
    {
        $arrayAsString = var_export($array, true);
        $arrayAsString =
            preg_replace('=^array \(=', '[', $arrayAsString);
        $arrayAsString =
            preg_replace('=\)$=', ']', $arrayAsString);
        $arrayAsString =
            preg_replace('= \=\> \R {2,}array \(=', ' => [', $arrayAsString);
        $arrayAsString =
            preg_replace('=(\R {2,})\),=', '$1],', $arrayAsString);
        return $arrayAsString;
    }

    /**
     * Removes directory recursively.
     * @param string $pathToDirectory
     * @return bool
     * @see https://stackoverflow.com/a/11267139/4223982
     */
    public static function remove_directory_recursively(
        string $pathToDirectory
    ) : bool
    {
        foreach (glob($pathToDirectory . '/*') as $pathToFile) {
            if (is_dir($pathToFile)) {
                self::remove_directory_recursively($pathToFile);
            } else {
                unlink($pathToFile);
            }
        }
        return rmdir($pathToDirectory);
    }

    /**
     * Sorts array by date.
     * For example, if the received array will be as the following:
     * ```
     * [
     *   [
     *     'title' => 'Title 2',
     *     'date'  => '1970-01-01',
     *   ],
     *   [
     *     'title' => 'Title 1',
     *     'date'  => '1969-12-31',
     *   ],
     *   [
     *     'title' => 'Title 3',
     *     'date'  => '1970-01-02',
     *   ],
     * ]
     * ```
     * then the result will be:
     * ```
     * [
     *   [
     *     'title' => 'Title 1',
     *     'date'  => '1969-12-31',
     *   ],
     *   [
     *     'title' => 'Title 2',
     *     'date'  => '1970-01-01',
     *   ],
     *   [
     *     'title' => 'Title 3',
     *     'date'  => '1970-01-02',
     *   ],
     * ]
     * ```
     * @param array  $array
     * @param string $key
     * @param bool   $asc
     * @return array
     * @see https://stackoverflow.com/a/6401744/4223982
     */
    public static function sort_by_date(
        array $array,
        string $key,
        bool $asc = true
    ) : array
    {
        usort($array, function ($a, $b) use ($key) {
            return strtotime($a[$key]) - strtotime($b[$key]);
        });
        if (!$asc) {
            $array = array_reverse($array);
        }
        return $array;
    }
}

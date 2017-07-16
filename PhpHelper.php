<?php

namespace w3lifer\phpHelper;

use DateTime;

/**
 * PHP helper.
 * @author Roman Grinyov <w3lifer@gmail.com>
 */
class PhpHelper
{
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
     * @see http://stackoverflow.com/a/2310591/4223982
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
     * @param      $csvString
     * @param bool $removeFirstLine
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
     * Example of the returned array:
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
     * @author http://php.net/ucfirst#57298
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
}

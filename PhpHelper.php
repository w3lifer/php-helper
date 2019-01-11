<?php

namespace w3lifer\phpHelper;

use DateTime;
use DateTimeZone;
use ZipArchive;

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
    ) : array {
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
     * Returns received array where keys are postfixed with specified postfix.
     * @param array  $array
     * @param string $postfix
     * @param bool   $recursively
     * @return array
     * @see https://stackoverflow.com/a/2608166/4223982
     */
    public static function add_postfix_to_array_keys(
        array $array,
        string $postfix,
        bool $recursively = true
    ) : array {
        $newArray = [];
        foreach ($array as $key => $value) {
            if ($recursively && is_array($value)) {
                $newArray[$key . $postfix] =
                    self::add_postfix_to_array_keys($value, $postfix);
                continue;
            }
            $newArray[$key . $postfix] = $value;
        }
        return $newArray;
    }

    /**
     * Returns the passed value with a zero prefix, if the value is less than
     * 1e<order>.
     * @param string $value
     * @param int    $order
     * @return string
     */
    public static function add_zero_prefix(
        string $value,
        int $order = 1
    ) : string {
        $times = 0;
        $orderValue = pow(10, $order);
        if ($value < $orderValue) {
            $times = strlen($orderValue) - strlen($value);
        }
        return str_repeat(0, $times) . $value;
    }

    /**
     * @param array  $array
     * @param string $afterKey
     * @param string $key
     * @param string $new
     * @return array
     * @see https://stackoverflow.com/a/21336407/4223982
     */
    public static function array_insert_after_key(
        array $array,
        string $afterKey,
        string $key,
        string $new
    ) : array {
        $pos = (int) array_search($afterKey, array_keys($array)) + 1;
        return
            array_merge(
                array_slice($array, 0, $pos),
                [$key => $new],
                array_slice($array, $pos)
            );
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
     * @param array  $values
     * @param string $valueWrapper
     * @return string
     */
    public static function create_sql_values_string(
        array $values,
        string $valueWrapper = '"'
    ) : string {
        $sqlValues = '(';
        foreach ($values as $value) {
            $sqlValues .= $valueWrapper . $value . $valueWrapper . ', ';
        }
        $sqlValues = rtrim($sqlValues, ', ');
        $sqlValues .= ')';
        return $sqlValues;
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
    ) : array {
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
     * Filter input array by passed params.
     * Example of input array:
     * ``` php
     * [
     *     ['firstname' => 'John', 'lastname' => 'Doe'],
     *     ['firstname' => 'Вася', 'lastname' => 'Пупкин'],
     * ]
     * ```
     * If passed params are `['firstname' => 'Jo']`, then result will be the
     * following:
     * ``` php
     * [
     *     ['firstname' => 'John', 'lastname' => 'Doe'],
     * ]
     * ```
     * If passed params are `['firstname' => 'jo']`, then result will be an
     * empty array (case-sensitive search).
     * @param array $inputArray
     * @param array $searchParams
     * @return array
     */
    public static function filter_list_of_arrays_by_key_value_pairs(
        array $inputArray,
        array $searchParams
    ) : array {
        foreach ($searchParams as $searchParamName => $searchParamValue) {
            if (
                !isset($inputArray[0][$searchParamName])
                ||
                $searchParamValue === ''
            ) {
                continue;
            }
            foreach ($inputArray as $rowIndex => $row) {
                $match = mb_strpos($row[$searchParamName], $searchParamValue);
                if ($match === false) {
                    unset($inputArray[$rowIndex]);
                }
            }
        }
        return $inputArray;
    }

    /**
     * @param string $absolutePathToImage
     * @return string
     */
    public static function get_base64_image(
        string $absolutePathToImage
    ) : string {
        $mimeType = mime_content_type($absolutePathToImage);
        $base64Image = base64_encode(file_get_contents($absolutePathToImage));
        return 'data:' . $mimeType . ';base64,' . $base64Image;
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
    ) : array {
        $dates[] = date($format, strtotime($startDate));
        $dateDiff = (new DateTime($startDate))->diff(new DateTime($endDate));
        for ($i = 1; $i <= $dateDiff->days; $i++) {
            $dates[] = date($format, strtotime($dates[$i - 1] . '+1 day'));
        }
        return $dates;
    }

    /**
     * Returns an array of file names in the specified directory.
     * Note that directories will not be listed in the returned array.
     * @param string $pathToDirectory
     * @param bool   $recursively
     * @param array  $fileExtensions
     * @param array  $result
     * @return array
     * @see https://stackoverflow.com/a/24784144/4223982
     */
    public static function get_files_in_directory(
        string $pathToDirectory,
        bool $recursively = false,
        array $fileExtensions = [],
        &$result = []
    ) : array {
        $fileNames = scandir($pathToDirectory);
        foreach ($fileNames as $fileName) {
            $path =
                realpath($pathToDirectory . DIRECTORY_SEPARATOR . $fileName);
            if (!is_dir($path)) {
                if ($fileExtensions) {
                    foreach ($fileExtensions as $fileExtension) {
                        if (preg_match('=\.' . $fileExtension .'$=i', $path)) {
                            $result[] = $path;
                            continue 2;
                        }
                    }
                } else {
                    $result[] = $path;
                }
            } else if (
                $recursively &&
                $fileName !== '.' &&
                $fileName !== '..'
            ) {
                self::get_files_in_directory(
                    $path,
                    $recursively,
                    $fileExtensions,
                    $result
                );
            }
        }
        return $result;
    }

    /**
     * @param int $dayOfWeek
     * @return int 0-6 (Monday-Sunday)
     */
    public static function get_normalized_day_of_week(int $dayOfWeek)
    {
        return $dayOfWeek === 7 ? 0 : $dayOfWeek;
    }

    /**
     * Utility function for getting random values with weighting.
     * Pass in an associative array, such as `['a' => 5, 'b' => 10, 'c' => 15]`.
     * An array like this means that "a" has a 5% chance of being selected, "b"
     * 45%, and "c" 50%. The return value is the array key, "a", "b", or "c" in
     * this case.
     * Note that the values assigned do not have to be percentages.
     * The values are simply relative to each other.
     * If one value weight was 2, and the other weight of 1, the value with the
     * weight of 2 has about a 66% chance of being selected.
     * Also note that weights should be integers.
     * @param array $weightedValues
     * @return bool|int|string
     * @see https://stackoverflow.com/a/11872928/4223982
     */
    public static function get_random_weighted_element(array $weightedValues)
    {
        $rand = mt_rand(1, (int) array_sum($weightedValues));
        foreach ($weightedValues as $key => $value) {
            $rand -= $value;
            if ($rand <= 0) {
                return $key;
            }
        }
        return false;
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
    ) : bool {
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
    ) : array {
        usort($array, function ($a, $b) use ($key) {
            return strtotime($a[$key]) - strtotime($b[$key]);
        });
        if (!$asc) {
            $array = array_reverse($array);
        }
        return $array;
    }

    /**
     * @param string $input
     * @param int    $multiplier
     * @param string $separator
     * @return string
     * @see https://php.net/str-repeat
     * @see https://php.net/str-repeat#88830
     */
    public static function str_repeat_with_separator(
        string $input,
        int $multiplier,
        string $separator = ''
    ) : string {
        return
            $multiplier === 0
                ? ''
                : str_repeat($input . $separator, $multiplier - 1) . $input;
    }

    /**
     * Extracts ZIP archive to the specified path.
     * @param string $pathToArchive
     * @param string $extractTo
     * @return bool|int TRUE on success, FALSE or error number on failure.
     * @see https://stackoverflow.com/a/8889126/4223982
     */
    public static function unzip(string $pathToArchive, string $extractTo)
    {
        $zipArchive = new ZipArchive();
        $result = $zipArchive->open($pathToArchive);
        if ($result === true) {
            $extracted = $zipArchive->extractTo($extractTo);
            $closed = $zipArchive->close();
            return $extracted && $closed;
        }
        return $result;
    }
}

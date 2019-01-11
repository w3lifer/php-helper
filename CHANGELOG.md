# Change Log

- [4.2.0 January 11, 2019](#420-january-11-2019)
- [4.1.0 April 8, 2018](#410-april-8-2018)
- [4.0.0 March 8, 2018](#400-march-8-2018)
- [3.2.0 March 4, 2018](#320-march-4-2018)
- [3.1.0 January 26, 2018](#310-january-26-2018)
- [3.0.0 October 3, 2017](#300-october-3-2017)
- [2.0.0 September 30, 2017](#200-september-30-2017)
- [1.2.0 July 16, 2017](#120-july-16-2017)
- [1.1.0 July 16, 2017](#110-july-16-2017)
- [1.0.0 July 16, 2017](#100-july-16-2017)

## 4.2.0 January 11, 2019

- Added:

``` php
array_insert_after_key(array $array, string $afterKey, string $key, string $new)
create_sql_values_string(array $values, string $valueWrapper = '"') : string
filter_list_of_arrays_by_key_value_pairs(array $inputArray, array $searchParams) : array
get_base64_image(string $absolutePathToImage) : string
```

## 4.1.0 April 8, 2018

- Added:

``` php
get_normalized_day_of_week(int $dayOfWeek) : int
```

## 4.0.0 March 8, 2018

- Added the ability to pass file extensions for `get_files_in_directory()` method

## 3.2.0 March 4, 2018

- Added:

``` php
get_files_in_directory(string $pathToDirectory, bool $recursively = false, &$result = []) : array
```

## 3.1.0 January 26, 2018

- Added:

``` php
str_repeat_with_separator(string $input, int $multiplier, string $separator = '') : string
```

## 3.0.0 October 3, 2017

- Removed `add_timezone_offset_to_timestamp()`: just use `$timestamp += PhpHelper::get_timezone_offset($timeZone)`
- Added `add_zero_prefix(string $value, int $order = 1) : string`

## 2.0.0 September 30, 2017

- Added:

``` php
get_timezone_offset(string $timeZone) : int
add_timezone_offset_to_timestamp(int $timestamp, string $timeZone) : int
remove_directory_recursively(string $pathToDirectory) : bool
get_random_weighted_element(array $weightedValues)
unzip(string $pathToArchive, string $extractTo)
```

- `add_suffix_to_array_keys()` renamed to `add_postfix_to_array_keys()`

## 1.2.0 July 16, 2017

- Added:

``` php
sort_by_date(array $array, string $key, bool $asc = true) : array
```

## 1.1.0 July 16, 2017

- Added:

``` php
add_prefix_to_array_keys(array $array, string $prefix, bool $recursively = true) : array
add_suffix_to_array_keys(array $array, string $suffix, bool $recursively = true) : array
```

## 1.0.0 July 16, 2017

- Initial release
- Added methods:

``` php
auth(array $credentials) : bool
clear_all_cookies() : bool
csv_string_to_array(string $csvString, bool $removeFirstLine = false) : array
get_dates_between_dates(string $startDate, string $endDate, string $format = 'Y-m-d') : array
is_ajax() : bool
mb_ucfirst(string $string) : string
pretty_var_export_soft(array $array) : string
pretty_var_export_hard(array $array) : string
```

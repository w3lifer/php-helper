# php-helper

- [Installation](#installation)
- [Methods](#methods)

## Installation

``` sh
composer require w3lifer/php-helper
```

## Methods

- `add_prefix_to_array_keys(array $array, string $prefix, bool $recursively = true) : array`
- `add_postfix_to_array_keys(array $array, string $postfix, bool $recursively = true) : array`
- `add_zero_prefix(string $value, int $order = 1) : string`
- `array_insert_after_key(array $array, string $afterKey, string $key, string $new)`
- `array_to_xml(array $data, SimpleXMLElement &$xmlData = null) : string`
- `auth(array $credentials) : bool`
- `clear_all_cookies() : bool`
- `create_sql_values_string(array $values, string $valueWrapper = '"') : string`
- `csv_string_to_array(string $csvString, bool $removeFirstLine = false) : array`
- `filter_list_of_arrays_by_key_value_pairs(array $inputArray, array $searchParams) : array`
- `get_base64_image(string $absolutePathToImage) : string`
- `get_dates_between_dates(string $startDate, string $endDate, string $format = 'Y-m-d') : array`
- `get_files_in_directory(string $pathToDirectory, bool $recursively = false, array $fileExtensions = [], &$result = []) : array`
- `get_full_url() : string`
- `get_normalized_day_of_week(int $dayOfWeek) : int`
- `get_random_weighted_element(array $weightedValues)`
- `get_timezone_offset(string $timeZone) : int`
- `is_ajax() : bool`
- `mb_ucfirst(string $string) : string`
- `pretty_var_export_soft(array $array) : string`
- `pretty_var_export_hard(array $array) : string`
- `put_array_to_csv_file(string $filename, array $array) : bool`
- `quick_sort(array $array) : array`
- `remove_directory_recursively(string $pathToDirectory) : bool`
- `remove_duplicates_from_multi_dimensional_array(array $array) : array`
- `sort_by_date(array $array, string $key, bool $asc = true) : array`
- `str_repeat_with_separator(string $input, int $multiplier, string $separator = '') : string`
- `unzip(string $pathToArchive, string $extractTo)`

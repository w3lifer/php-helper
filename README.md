# PHP Helper

- [Installation](#installation)
- [Methods](#methods)

## Installation

``` shell
composer require w3lifer/php-helper
```

## Methods

- `add_prefix_to_array_keys(array $array, string $prefix, bool $recursively = true) : array`
- `add_postfix_to_array_keys(array $array, string $postfix, bool $recursively = true) : array`
- `add_zero_prefix(string $value, int $order = 1) : string`
- `auth(array $credentials) : bool`
- `clear_all_cookies() : bool`
- `csv_string_to_array(string $csvString, bool $removeFirstLine = false) : array`
- `get_dates_between_dates(string $startDate, string $endDate, string $format = 'Y-m-d') : array`
- `get_files_in_directory(string $pathToDirectory, bool $recursively = false, array $fileExtensions = [], &$result = []) : array`
- `get_random_weighted_element(array $weightedValues)`
- `get_timezone_offset(string $timeZone) : int`
- `is_ajax() : bool`
- `mb_ucfirst(string $string) : string`
- `pretty_var_export_soft(array $array) : string`
- `pretty_var_export_hard(array $array) : string`
- `remove_directory_recursively(string $pathToDirectory) : bool`
- `sort_by_date(array $array, string $key, bool $asc = true) : array`
- `str_repeat_with_separator(string $input, int $multiplier, string $separator = '') : string`
- `unzip(string $pathToArchive, string $extractTo)`

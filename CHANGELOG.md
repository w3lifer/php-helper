# Change Log

- [1.2.0 July 16, 2017](#120-july-16-2017)
- [1.1.0 July 16, 2017](#110-july-16-2017)
- [1.0.0 July 16, 2017](#100-july-16-2017)

## 1.2.0 July 16, 2017

- Added method:

``` php
sort_by_date(array $array, string $key, bool $asc = true) : array
```

## 1.1.0 July 16, 2017

- Added methods:

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

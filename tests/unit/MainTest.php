<?php

use w3lifer\phpHelper\PhpHelper;

class MainTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var string
     */
    private $pathToDataDirectory;

    protected function _before()
    {
        $this->pathToDataDirectory =
            realpath(__DIR__ . '/../data-for-unit-tests');
    }

    protected function _after()
    {
    }

    /* ---------------------------------------------------------------------- */

    public function test_add_prefix_to_array_keys()
    {
        $this->assertEquals(
            [],
            PhpHelper::add_prefix_to_array_keys([], '')
        );
        $this->assertEquals(
            ['a' => 1, 'b' => 2],
            PhpHelper::add_prefix_to_array_keys(['a' => 1, 'b' => 2], '')
        );
        $this->assertEquals(
            ['_a' => 1, '_b' => 2],
            PhpHelper::add_prefix_to_array_keys(['a' => 1, 'b' => 2], '_')
        );
        $this->assertEquals(
            [
                '_a' => 1,
                '_b' => 2,
                '_c' => ['_a' => 11, '_b' => 22]
            ],
            PhpHelper::add_prefix_to_array_keys([
                'a' => 1,
                'b' => 2,
                'c' => ['a' => 11, 'b' => 22]
            ], '_')
        );
        $this->assertEquals(
            [
                '_a' => 1,
                '_b' => 2,
                '_c' => ['a' => 11, 'b' => 22]
            ],
            PhpHelper::add_prefix_to_array_keys([
                'a' => 1,
                'b' => 2,
                'c' => ['a' => 11, 'b' => 22]
            ], '_', false)
        );
    }

    public function test_add_postfix_to_array_keys()
    {
        $this->assertEquals(
            [],
            PhpHelper::add_postfix_to_array_keys([], '')
        );
        $this->assertEquals(
            ['a' => 1, 'b' => 2],
            PhpHelper::add_postfix_to_array_keys(['a' => 1, 'b' => 2], '')
        );
        $this->assertEquals(
            ['a_' => 1, 'b_' => 2],
            PhpHelper::add_postfix_to_array_keys(['a' => 1, 'b' => 2], '_')
        );
        $this->assertEquals(
            [
                'a_' => 1,
                'b_' => 2,
                'c_' => ['a_' => 11, 'b_' => 22]
            ],
            PhpHelper::add_postfix_to_array_keys([
                'a' => 1,
                'b' => 2,
                'c' => ['a' => 11, 'b' => 22]
            ], '_')
        );
        $this->assertEquals(
            [
                'a_' => 1,
                'b_' => 2,
                'c_' => ['a' => 11, 'b' => 22]
            ],
            PhpHelper::add_postfix_to_array_keys([
                'a' => 1,
                'b' => 2,
                'c' => ['a' => 11, 'b' => 22]
            ], '_', false)
        );
    }

    public function test_add_zero_prefix()
    {
        $this->assertEquals('00', PhpHelper::add_zero_prefix(0));
        $this->assertEquals('01', PhpHelper::add_zero_prefix(1));
        $this->assertEquals('10', PhpHelper::add_zero_prefix(10));
        $this->assertEquals('11', PhpHelper::add_zero_prefix(11));

        // ---------------------------------------------------------------------

        $this->assertEquals('000', PhpHelper::add_zero_prefix(0, 2));
        $this->assertEquals('001', PhpHelper::add_zero_prefix(1, 2));
        $this->assertEquals('010', PhpHelper::add_zero_prefix(10, 2));
        $this->assertEquals('011', PhpHelper::add_zero_prefix(11, 2));

        $this->assertEquals('100', PhpHelper::add_zero_prefix(100, 2));
        $this->assertEquals('111', PhpHelper::add_zero_prefix(111, 2));

        // ---------------------------------------------------------------------

        $this->assertEquals('0000', PhpHelper::add_zero_prefix(0, 3));
        $this->assertEquals('0001', PhpHelper::add_zero_prefix(1, 3));
        $this->assertEquals('0010', PhpHelper::add_zero_prefix(10, 3));
        $this->assertEquals('0011', PhpHelper::add_zero_prefix(11, 3));

        $this->assertEquals('0100', PhpHelper::add_zero_prefix(100, 3));
        $this->assertEquals('0111', PhpHelper::add_zero_prefix(111, 3));

        $this->assertEquals('1000', PhpHelper::add_zero_prefix(1000, 3));
        $this->assertEquals('1111', PhpHelper::add_zero_prefix(1111, 3));
    }

    public function test_auth()
    {
        $this->assertFalse(PhpHelper::auth([]));

        $_SERVER['PHP_AUTH_USER'] = 'hello';
        $_SERVER['PHP_AUTH_PW']   = 'world';
        $this->assertFalse(PhpHelper::auth([]));
        $this->assertFalse(PhpHelper::auth([
            'root' => 'toor',
        ]));

        $_SERVER['PHP_AUTH_USER'] = 'root';
        $_SERVER['PHP_AUTH_PW']   = 'toor';
        $this->assertFalse(PhpHelper::auth([]));
        $this->assertTrue(PhpHelper::auth([
            'root' => 'toor',
        ]));
    }

    public function test_clear_all_cookies()
    {
        $this->assertFalse(PhpHelper::clear_all_cookies());
        $_SERVER['HTTP_COOKIE'] = 'first=one';
        $this->assertTrue(PhpHelper::clear_all_cookies());
        $_SERVER['HTTP_COOKIE'] = 'first=one; second=two';
        $this->assertTrue(PhpHelper::clear_all_cookies());
        setcookie(111, 222);
        $this->assertTrue(PhpHelper::clear_all_cookies());
        setcookie(111, 222);
        setcookie(333, 444);
        $this->assertTrue(PhpHelper::clear_all_cookies());
    }

    public function test_csv_string_to_array()
    {
        $this->assertEquals([['']], PhpHelper::csv_string_to_array(''));
        $this->assertEquals([[1]], PhpHelper::csv_string_to_array(1));
        $this->assertEquals([[123]], PhpHelper::csv_string_to_array(123));
        $this->assertEquals([['1']], PhpHelper::csv_string_to_array('1'));
        $this->assertEquals([['123']], PhpHelper::csv_string_to_array('123'));
        $this->assertEquals(
            [
                ['First name', 'Last name'],
                ['John', 'Doe'],
                ['Richard', 'Roe'],
            ],
            PhpHelper::csv_string_to_array('
                First name,Last name
                John,Doe
                "Richard", "Roe"
            ')
        );
        $this->assertEquals(
            [
                ['John', 'Doe'],
                ['Richard', 'Roe'],
            ],
            PhpHelper::csv_string_to_array('
                First name,Last name
                John,Doe
                "Richard", "Roe"
            ', true)
        );
    }

    public function test_get_dates_between_dates()
    {
        $this->assertEquals(
            ['1969-12-31', '1970-01-01', '1970-01-02'],
            PhpHelper::get_dates_between_dates('1969-12-31', '1970-01-02')
        );
        $this->assertEquals(
            ['12/31/1969', '01/01/1970', '01/02/1970'],
            PhpHelper::get_dates_between_dates(
                '12/31/1969',
                '01/02/1970',
                'm/d/Y'
            )
        );
    }

    public function test_get_files_in_directory()
    {
        $paths = scandir($this->pathToDataDirectory);
        $fileNames= [];
        foreach ($paths as $path) {
            $fileName = $this->pathToDataDirectory . '/' . $path;
            if (is_file($fileName)) {
                $fileNames[] = $fileName;
            }
        }

        $this->assertEquals(
            $fileNames,
            PhpHelper::get_files_in_directory($this->pathToDataDirectory)
        );
    }

    public function test_get_normalized_day_of_week()
    {
        $this->assertEquals(0, PhpHelper::get_normalized_day_of_week(7));
        $this->assertEquals(0, PhpHelper::get_normalized_day_of_week('7'));
        $this->assertEquals(1, PhpHelper::get_normalized_day_of_week(1));
        $this->assertEquals(1, PhpHelper::get_normalized_day_of_week('1'));
        $this->assertEquals(2, PhpHelper::get_normalized_day_of_week(2));
        $this->assertEquals(2, PhpHelper::get_normalized_day_of_week('2'));
        $this->assertEquals(3, PhpHelper::get_normalized_day_of_week(3));
        $this->assertEquals(3, PhpHelper::get_normalized_day_of_week('3'));
        $this->assertEquals(4, PhpHelper::get_normalized_day_of_week(4));
        $this->assertEquals(4, PhpHelper::get_normalized_day_of_week('4'));
        $this->assertEquals(5, PhpHelper::get_normalized_day_of_week(5));
        $this->assertEquals(5, PhpHelper::get_normalized_day_of_week('5'));
        $this->assertEquals(6, PhpHelper::get_normalized_day_of_week(6));
        $this->assertEquals(6, PhpHelper::get_normalized_day_of_week('6'));
    }

    public function test_get_timezone_offset()
    {
        $this->assertEquals(
            10800,
            PhpHelper::get_timezone_offset('Europe/Minsk')
        );
        $this->assertEquals(
            -25200,
            PhpHelper::get_timezone_offset('America/Los_Angeles')
        );
    }

    public function test_is_ajax()
    {
        $this->assertFalse(PhpHelper::is_ajax());

        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $this->assertTrue(PhpHelper::is_ajax());
    }

    public function test_mb_ucfirst()
    {
        $this->assertEquals(123, PhpHelper::mb_ucfirst(123));
        $this->assertEquals('Hello', PhpHelper::mb_ucfirst('hello'));
        $this->assertEquals('Hello', PhpHelper::mb_ucfirst('Hello'));
        $this->assertEquals('Привет', PhpHelper::mb_ucfirst('привет'));
        $this->assertEquals('Привет', PhpHelper::mb_ucfirst('Привет'));
    }

    public function test_pretty_var_export_soft()
    {
        $this->assertEquals(<<<'NOWDOC'
array (
  'a' => 1,
  'b' => array (
    'c' => 3,
  ),
)
NOWDOC
            , PhpHelper::pretty_var_export_soft([
                'a' => 1,
                'b' => [
                    'c' => 3,
                ],
            ]));
    }

    public function test_pretty_var_export_hard()
    {
        $this->assertEquals(<<<'NOWDOC'
[
  'a' => 1,
  'b' => [
    'c' => 3,
  ],
]
NOWDOC
            , PhpHelper::pretty_var_export_hard([
                'a' => 1,
                'b' => [
                    'c' => 3,
                ],
            ]));
    }

    public function test_remove_directory_recursively()
    {
        $pathToTmpDir = __DIR__ . '/tmp';
        mkdir($pathToTmpDir);
        file_put_contents($pathToTmpDir . '/tmp.txt', 'Lorem ipsum ...');
        mkdir($pathToTmpDir . '/tmp');
        file_put_contents($pathToTmpDir . '/tmp/tmp.txt', 'Lorem ipsum ...');
        $this->assertTrue(
            PhpHelper::remove_directory_recursively($pathToTmpDir)
        );
    }

    public function test_sort_by_date()
    {
        $this->assertEquals(
            [
                [
                    'title' => 'Title 1',
                    'date'  => '1969-12-31',
                ],
                [
                    'title' => 'Title 2',
                    'date'  => '1970-01-01',
                ],
                [
                    'title' => 'Title 3',
                    'date'  => '1970-01-02',
                ],
            ],
            PhpHelper::sort_by_date([
                [
                    'title' => 'Title 2',
                    'date'  => '1970-01-01',
                ],
                [
                    'title' => 'Title 1',
                    'date'  => '1969-12-31',
                ],
                [
                    'title' => 'Title 3',
                    'date'  => '1970-01-02',
                ],
            ], 'date')
        );
        $this->assertEquals(
            [
                [
                    'title' => 'Title 3',
                    'date'  => '1970-01-02',
                ],
                [
                    'title' => 'Title 2',
                    'date'  => '1970-01-01',
                ],
                [
                    'title' => 'Title 1',
                    'date'  => '1969-12-31',
                ],
            ],
            PhpHelper::sort_by_date([
                [
                    'title' => 'Title 2',
                    'date'  => '1970-01-01',
                ],
                [
                    'title' => 'Title 1',
                    'date'  => '1969-12-31',
                ],
                [
                    'title' => 'Title 3',
                    'date'  => '1970-01-02',
                ],
            ], 'date', false)
        );
    }

    public function test_str_repeat_with_separator()
    {
        $this->assertTrue(
            PhpHelper::str_repeat_with_separator('', 0) === ''
        );
        $this->assertTrue(
            PhpHelper::str_repeat_with_separator('', 1) === ''
        );
        $this->assertTrue(
            PhpHelper::str_repeat_with_separator('', 2) === ''
        );
        $this->assertTrue(
            PhpHelper::str_repeat_with_separator('a', 0) === ''
        );
        $this->assertTrue(
            PhpHelper::str_repeat_with_separator('a', 1) === 'a'
        );
        $this->assertTrue(
            PhpHelper::str_repeat_with_separator('a', 2) === 'aa'
        );
        $this->assertTrue(
            PhpHelper::str_repeat_with_separator('a', 0, '') === ''
        );
        $this->assertTrue(
            PhpHelper::str_repeat_with_separator('a', 1, '') === 'a'
        );
        $this->assertTrue(
            PhpHelper::str_repeat_with_separator('a', 2, '') === 'aa'
        );
        $this->assertTrue(
            PhpHelper::str_repeat_with_separator('a', 0, '|') === ''
        );
        $this->assertTrue(
            PhpHelper::str_repeat_with_separator('a', 1, '|') === 'a'
        );
        $this->assertTrue(
            PhpHelper::str_repeat_with_separator('a', 2, '|') === 'a|a'
        );
    }

    public function test_unzip()
    {
        $pathToArchive = $this->pathToDataDirectory . '/tmp.zip';
        $extractTo = $this->pathToDataDirectory . '/tmp';
        $this->assertFalse(PhpHelper::unzip($pathToArchive, ''));
        $this->assertTrue(PhpHelper::unzip($pathToArchive, $extractTo));
        $this->assertTrue(PhpHelper::remove_directory_recursively($extractTo));
    }
}

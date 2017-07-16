<?php

use w3lifer\phpHelper\PhpHelper;

class MainTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /* ---------------------------------------------------------------------- */

    public function test_add_prefix_to_array_keys()
    {
        $this->assertEquals(
            PhpHelper::add_prefix_to_array_keys([], ''),
            []
        );
        $this->assertEquals(
            PhpHelper::add_prefix_to_array_keys(['a' => 1, 'b' => 2], ''),
            ['a' => 1, 'b' => 2]
        );
        $this->assertEquals(
            PhpHelper::add_prefix_to_array_keys(['a' => 1, 'b' => 2], '_'),
            ['_a' => 1, '_b' => 2]
        );
        $this->assertEquals(
            PhpHelper::add_prefix_to_array_keys([
                'a' => 1,
                'b' => 2,
                'c' => ['a' => 11, 'b' => 22]
            ], '_'),
            [
                '_a' => 1,
                '_b' => 2,
                '_c' => ['_a' => 11, '_b' => 22]
            ]
        );
        $this->assertEquals(
            PhpHelper::add_prefix_to_array_keys([
                'a' => 1,
                'b' => 2,
                'c' => ['a' => 11, 'b' => 22]
            ], '_', false),
            [
                '_a' => 1,
                '_b' => 2,
                '_c' => ['a' => 11, 'b' => 22]
            ]
        );
    }

    public function test_add_suffix_to_array_keys()
    {
        $this->assertEquals(
            PhpHelper::add_suffix_to_array_keys([], ''),
            []
        );
        $this->assertEquals(
            PhpHelper::add_suffix_to_array_keys(['a' => 1, 'b' => 2], ''),
            ['a' => 1, 'b' => 2]
        );
        $this->assertEquals(
            PhpHelper::add_suffix_to_array_keys(['a' => 1, 'b' => 2], '_'),
            ['a_' => 1, 'b_' => 2]
        );
        $this->assertEquals(
            PhpHelper::add_suffix_to_array_keys([
                'a' => 1,
                'b' => 2,
                'c' => ['a' => 11, 'b' => 22]
            ], '_'),
            [
                'a_' => 1,
                'b_' => 2,
                'c_' => ['a_' => 11, 'b_' => 22]
            ]
        );
        $this->assertEquals(
            PhpHelper::add_suffix_to_array_keys([
                'a' => 1,
                'b' => 2,
                'c' => ['a' => 11, 'b' => 22]
            ], '_', false),
            [
                'a_' => 1,
                'b_' => 2,
                'c_' => ['a' => 11, 'b' => 22]
            ]
        );
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
            PhpHelper::get_dates_between_dates('1969-12-31', '1970-01-02'),
            ['1969-12-31', '1970-01-01', '1970-01-02']
        );
        $this->assertEquals(
            PhpHelper::get_dates_between_dates(
                '12/31/1969',
                '01/02/1970',
                'm/d/Y'
            ),
            ['12/31/1969', '01/01/1970', '01/02/1970']
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
}

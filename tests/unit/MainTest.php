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

    public function test_array_insert_after_key()
    {
        $this->assertEquals(
            [
            'one' => 'first',
            'two' => 'second',
            'three' => 'third',
            ],
            PhpHelper::array_insert_after_key([
                'one' => 'first',
                'three' => 'third',
            ], 'one', 'two', 'second')
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

    public function test_create_sql_values_string()
    {
        $this->assertEquals(
            '("one", "two", "three")',
            PhpHelper::create_sql_values_string([
                'one',
                'two',
                'three',
            ]));
        $this->assertEquals(
            '(\'first\', \'second\', \'third\')',
            PhpHelper::create_sql_values_string([
                'first',
                'second',
                'third',
            ], '\''));
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

    public function test_filter_list_of_arrays_by_key_value_pairs()
    {
        $this->assertEquals(
            [
                ['firstname' => 'John', 'lastname' => 'Doe'],
            ],
            PhpHelper::filter_list_of_arrays_by_key_value_pairs(
                [
                    ['firstname' => 'John', 'lastname' => 'Doe'],
                    ['firstname' => 'Вася', 'lastname' => 'Пупкин'],
                ],
                ['firstname' => 'Jo']
            )
        );
        $this->assertEquals(
            [],
            PhpHelper::filter_list_of_arrays_by_key_value_pairs(
                [
                    ['firstname' => 'John', 'lastname' => 'Doe'],
                    ['firstname' => 'Вася', 'lastname' => 'Пупкин'],
                ],
                ['firstname' => 'jo']
            )
        );
    }

    public function test_get_base64_image()
    {
        $this->assertEquals(
            'data:image/x-icon;base64,AAABAAIAEBAAAAEAIAAoBQAAJgAAACAgAAABACAAKBQAAE4FAAAoAAAAEAAAACAAAAABACAAAAAAAAAFAAAAAAAAAAAAAAAAAAAAAAAA////hf////n////////////////////////////////////////////////////////////////////6////hf////z///////////////////////////////////////////////////////////////////////////////n/////////////////////////////////////////////////////////////////////////////////////////////////////5NC7/+LNtv///////////9/JsP/t4NL//////8yogP+dVwr/nVcK/9rAo////////////////////////////7+RX/+7ilX///////////+2gkj/2sCj/+vczP+dVwr/17qb/8KWZP+dVwr/1rmY//////////////////////+scC7/pWUf////////////nVcK/8KWZf/28On/5dK+///////+/v3/nVcK/692Nv/////////////////07OP/nVcK/51XCv/8+vf/7N7P/51XCv+lZR///fz7/////////////////59bD/+scS7/////////////////1LWS/6RkHP+oaib/3cWq/9KzkP+kYhn/pmch/+TQu////////////+HLs/+dVwr/xJpr/////////////////7F6Pf/Hn3T/17qb/650NP+xeTv/3MKn/7R/Rf/Jonj//////6xxLv+dVwr/vo9c//jy7f////////////r28/+dVwr/3sas//v49f+dVwr/nVcK///////NqoP/p2kj//////+wdzn/p2ci///////////////////////exqv/nVcK//fx6///////pmch/7B4Ov//////6dnH/51XCv/17+f/+vbz/59aD//dxar/////////////////uolT/6lrKP///////////6JfFv/KpXv///////79/P+iYBb/uYhR//Xu5v+xeTv/oFwR/+jYxv///////////6doIv/NqoP//////+nayf+hXhT/7N/Q////////////yqV7/51XCv+gXRL/pmYf/51XCv/Hn3P////////////////////////////////////////////////////////////////////////////////////////////////8///////////////////////////////////////////////////////////////////////////////5////hf////n////////////////////////////////////////////////////////////////////5////hQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAoAAAAIAAAAEAAAAABACAAAAAAAAAUAAAAAAAAAAAAAAAAAAAAAAAA////Bv///5H////u///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////u////kf///wb///+Q////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////if///+3////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////t/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////+bUwP/gyrL/////////////////////////////////1LWT//bv6P//////////////////////3MSp/7R/Rf+mZh//qGom/7yMV//p2sn/////////////////////////////////////////////////////////////////x55y/72OW/////////////////////////////37+f+payj/5NC7/////////////////8ulfP+dVwr/nVcK/51XCv+dVwr/nVcK/6BcEf/hy7P////////////////////////////////////////////////////////+/v+vdTf/pmch//z69///////////////////////693N/51XCv/Lpn3////////////fybD/nVcK/51XCv+udTX/zquE/8GVY/+dVwr/nVcK/6RjGv/17eX/////////////////////////////////////////////////8efb/59aD/+dVwr/6tvK///////////////////////Qr4r/nVcK/7F5O////////v38/650NP+dVwr/tH9F//r28v///////////8qjef+dVwr/nVcK/86rhP/////////////////////////////////////////////////YvJ3/nVcK/51XCv/Oq4X//////////////////////7R/RP+dVwr/n1sP//Hn3P/+/v3/38mw/7uKVf/r3c7/////////////////9/Ls/6RjGv+dVwr/sns+/////////////////////////////////////////////////7uKVP+dVwr/nVcK/7J7P//////////////////z6+H/oF0S/51XCv+dVwr/17qa////////////////////////////////////////////t4NL/51XCv+nZyL////////////////////////////////////////////48+7/pGQc/51XCv+dVwr/oFwR//Lo3f///////////9rAo/+dVwr/nVcK/51XCv+7ilX///////////////////////////////////////////++kF3/nVcK/6RiGf///////////////////////////////////////////+LNtv+dVwr/nVcK/51XCv+dVwr/2b2f////////////vo9c/51XCv+dVwr/nVcK/6VkHP/48+7//////////////////////////////////////7aBSP+dVwr/qGom////////////////////////////////////////////xp1w/51XCv+qbiz/uIZO/51XCv+8jFf///////r28v+mZh//nVcK/7+RX/+fWw//nVcK/+POuP/////////////////////////////////07eT/omAX/51XCv+2gkj///////////////////////////////////////37+f+rby3/nVcK/8KXZ//hzLT/nVcK/6VlHv/9+/n/5tTB/51XCv+hXhT/7uLV/6ptKf+dVwr/xp1x////////////////////////////9/Hq/7qJU/+dVwr/nVcK/9e6mv//////////////////////////////////////693N/51XC/+dVwr/28Km//r28/+iXxb/nVcK/+jXxf/NqYH/nVcK/7R/RP//////vpBe/51XCv+scTD//fz6///////w5dn/wZVj/7yMV/+naCL/nVcK/51XCv+vdzj/+/j0///////////////////////////////////////Qr4r/nVcK/6BcEf/y6d7//////7aCSP+dVwr/xJpr/7N8P/+dVwr/z62H///////av6L/nVcK/55YC//s39D//////+vczP+dVwr/nVcK/51XCv+dVwr/s3w///Lp3////////////////////////////////////////////7R/Rf+dVwr/sXo9///+/v//////0rOQ/51XCv+gXRL/n1oN/51XC//q28v///////Lp3/+gXBH/nVcK/9Kyj////////v79/7yMV/+dVwr/nVcK/8+shv/9+/n////////////////////////////////////////////z6+L/oV4U/51XCv/MqID////////////u4dT/nlkN/51XCv+dVwr/q28t//37+f////////7+/7N8P/+dVwr/toJJ////////////8OXa/6FeFP+dVwr/vpBe/////////////////////////////////////////////////9vBpf+dVwr/nVcK/+jXxP////////////38+/+udDT/nVcK/51XCv/Fm23/////////////////zquE/51XCv+iYBb/9e7m////////////zKd//51XCv+dVwr/4cy0////////////////////////////////////////////vpBd/51XCv+oaib/+/j0/////////////////8qle/+dVwr/nVcK/+HLs//////////////////o2Mb/nVcK/51XCv/dxar////////////59fH/qWso/51XCv+tcjH//Pr3//////////////////////////////////r28/+nZyL/nVcK/8CSYf//////////////////////zKiA/51XCv+kYhn/9/Ls//////////////////z59v+payj/nVcK/8GVY//////////////////izbb/n1oN/51XCv/Ss5D/////////////////////////////////5dK+/51XCv+dVwr/3MOo//////////////////79/P+vdzj/nVcK/7mIUf///////////////////////////8GVY/+dVwr/o2IZ/8+shv/RsIz/0bCM/9Cvi/+kYxr/nVcK/6RjGv/07eT////////////////////////////Hn3T/nVcK/6BdEv/07eT/////////////////7uHU/51XCv+dVwr/1rmZ////////////////////////////3sar/51XCv+dVwr/nVcK/51XCv+dVwr/nVcK/51XCv+dVwr/nVcK/8OYaf///////////////////////////7R/Rf+kYxr/uohS///////////////////////Xupv/pGIZ/6ZnIf/w5tr////////////////////////////17ub/qWsm/6RjGv+kYxr/pGMa/6RjGv+kYxr/pGMa/6RjGv+kYxr/t4NL/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////+3////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////t////kP///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////4j///8G////kf///+3//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////+7///+R////BgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=',
            PhpHelper::get_base64_image(
                $this->pathToDataDirectory . '/images/w3.org.favicon.ico'
            )
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

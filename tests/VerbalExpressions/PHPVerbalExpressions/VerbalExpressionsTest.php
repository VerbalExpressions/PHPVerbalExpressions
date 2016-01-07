<?php

use VerbalExpressions\PHPVerbalExpressions\VerbalExpressions;

class VerbalExpressionsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideValidUrls
     * @group        functional
     */
    public function testShouldPassWhenValidUrlGiven($url)
    {
        $regex = new VerbalExpressions();
        $this->buildUrlPattern($regex);

        $this->assertTrue($regex->test($url));


        $regex = new VerbalExpressions();
        $this->buildUrlPatternAliased($regex);

        $this->assertTrue($regex->test($url));
    }

    public static function provideValidUrls()
    {
        return array(
            array('http://github.com'),
            array('http://www.github.com'),
            array('https://github.com'),
            array('https://www.github.com'),
            array('https://github.com/blog'),
            array('https://foobar.github.com')
        );
    }

    /**
     * @dataProvider provideInvalidUrls
     * @group        functional
     */
    public function testShouldFailWithInvalidUrls($url)
    {
        $regex = new VerbalExpressions();
        $this->buildUrlPattern($regex);

        $this->assertFalse($regex->test($url));
    }

    public static function provideInvalidUrls()
    {
        return array(
            array(' http://github.com'),
            array('foo'),
            array('htps://github.com'),
            array('http:/github.com'),
            array('https://github.com /blog'),
        );
    }

    protected function buildUrlPattern(VerbalExpressions $regex)
    {
        return $regex->startOfLine()
            ->then("http")
            ->maybe("s")
            ->then("://")
            ->maybe("www.")
            ->anythingBut(" ")
            ->endOfLine();
    }

    protected function buildUrlPatternAliased(VerbalExpressions $regex)
    {
        return $regex->startOfLine()
            ->find("http")
            ->maybe("s")
            ->find("://")
            ->maybe("www.")
            ->anythingBut(" ")
            ->endOfLine();
    }


    public function testTest(){
        $regex = new VerbalExpressions();
        $regex->find('regex');

        $this->assertTrue($regex->test('testing regex string'));
        $this->assertFalse($regex->test('testing string'));

        $regex->stopAtFirst();

        $this->assertEquals(1, $regex->test('testing regex string'));
        $this->assertFalse($regex->test('testing string'));
    }

    /**
    * @depends testTest
    */
    public function testThenAfterStartOfLine()
    {
        $regex = new VerbalExpressions();
        $regex->startOfLine()
            ->then('a')
            ->endOfLine();

        $this->assertTrue($regex->test('a'));
        $this->assertFalse($regex->test('ba'));
    }

    public function testThenSomewhere()
    {
        $regex = new VerbalExpressions();
        $regex->startOfLine(false)
            ->then('a')
            ->endOfLine(false);

        $this->assertTrue($regex->test('a'));
        $this->assertTrue($regex->test('ba'));
    }

    /**
     * @dataProvider provideAnything
     */
    public function testAnything($needle)
    {
        $regex = new VerbalExpressions();
        $regex->startOfLine()
            ->anything()
            ->endOfLine();

        $this->assertTrue($regex->test($needle));
    }

    public static function provideAnything()
    {
        return array(
            array('a'),
            array('foo'),
            array('bar'),
            array('!'),
            array(' dfs fdslf sdlfk '),
            array('t ( - _ - t )'),
        );
    }

    public function testAnythingBut()
    {
        $regex = new VerbalExpressions();
        $regex->startOfLine()
            ->anythingBut('a')
            ->endOfLine();

        $this->assertTrue($regex->test('bcdefg h I A'));
        $this->assertFalse($regex->test('a'));
        $this->assertFalse($regex->test('fooa'));
    }

    public function testAnythingButQuote()
    {
        $regex = new VerbalExpressions();
        $regex->startOfLine()
            ->anythingBut('[')
            ->endOfLine();

        $this->assertTrue($regex->test('abcd'));
        $this->assertTrue($regex->test('ab\cd'));
        $this->assertFalse($regex->test('ab[cd'));
        $this->assertFalse($regex->test('['));
        $this->assertFalse($regex->test('['));
        $this->assertFalse($regex->test('\['));
    }

    public function testSomething()
    {
        $regex = new VerbalExpressions();
        $regex->startOfLine()
            ->something()
            ->endOfLine();

        $this->assertTrue($regex->test('foobar'));
        $this->assertTrue($regex->test('foobar!'));
        $this->assertTrue($regex->test('foo bar'));
        $this->assertFalse($regex->test(''));
    }

    public function testSomethingBut()
    {
        $regex = new VerbalExpressions();
        $regex->startOfLine()
            ->somethingBut('a')
            ->endOfLine();

        $this->assertTrue($regex->test('foobr'));
        $this->assertTrue($regex->test('foobr!'));
        $this->assertTrue($regex->test('foo br'));
        $this->assertFalse($regex->test('a'));
        $this->assertFalse($regex->test('bar'));
        $this->assertFalse($regex->test('foo bar'));
        $this->assertFalse($regex->test(''));
    }

    public function testBr()
    {
        $regex = new VerbalExpressions();
        $regex->startOfLine()
            ->something()
            ->br()
            ->something()
            ->endOfLine();

        $this->assertTrue($regex->test("foo\nbar"));
        $this->assertFalse($regex->test("foo bar"));
    }

    public function testLineBreak()
    {
        $regex = new VerbalExpressions();
        $regex->startOfLine()
            ->something()
            ->lineBreak()
            ->something()
            ->endOfLine();

        $this->assertTrue($regex->test("foo\nbar"));
        $this->assertFalse($regex->test("foo bar"));
    }

    public function testTab()
    {
        $regex = new VerbalExpressions();
        $regex->startOfLine()
            ->something()
            ->tab()
            ->something()
            ->endOfLine();

        $this->assertTrue($regex->test("foo\tbar"));
        $this->assertFalse($regex->test("foo bar"));
    }

    public function testWord()
    {
        $regex = new VerbalExpressions();
        $regex->startOfLine()
            ->word()
            ->endOfLine();

        $this->assertTrue($regex->test('abcdefghijklmnopqrstuvwxyz0123456789_'));
        $this->assertTrue($regex->test('ABCDEFGHIJKLMNOPQRSTUVWXYZ'));
        $this->assertTrue($regex->test('a_c'));
        $this->assertFalse($regex->test('a-b'));
        $this->assertFalse($regex->test('a b'));
        $this->assertFalse($regex->test('a!b'));
    }

    public function testAny()
    {
        $regex = new VerbalExpressions();
        $regex->startOfLine()
            ->any('a1M')
            ->endOfLine();

        $this->assertTrue($regex->test('a'));
        $this->assertTrue($regex->test('1'));
        $this->assertTrue($regex->test('M'));
        $this->assertFalse($regex->test('b'));
        $this->assertFalse($regex->test(''));
        $this->assertFalse($regex->test(' '));
    }

    public function testAnyOf()
    {
        $regex = new VerbalExpressions();
        $regex->startOfLine()
            ->anyOf('a1M')
            ->endOfLine();

        $this->assertTrue($regex->test('a'));
        $this->assertTrue($regex->test('1'));
        $this->assertTrue($regex->test('M'));
        $this->assertFalse($regex->test('b'));
        $this->assertFalse($regex->test(''));
        $this->assertFalse($regex->test(' '));
    }


    public function testGetRegex(){
        $regex = new VerbalExpressions();
        $regex->startOfLine()
            ->range(0, 9, 'a', 'z', 'A', 'Z')
            ->multiple('');

        $this->assertEquals('/^[0-9a-zA-Z]+/m', $regex->getRegex());
        $this->assertEquals('/^[0-9a-zA-Z]+/m', $regex->__toString());
        $this->assertEquals('/^[0-9a-zA-Z]+/m', (string)$regex);
        $this->assertEquals('/^[0-9a-zA-Z]+/m', $regex . '');
    }

    /**
    * @depends testGetRegex
    */
    public function testGetRegex_multiple(){
        $regex = new VerbalExpressions();
        $regex->startOfLine()
            ->multiple('regex');

        $this->assertEquals('/^regex+/m', $regex->getRegex());
    }

    /**
    * @expectedException InvalidArgumentException
    */
    public function testRange_throwsException(){
      $regex = new VerbalExpressions();
      $regex->range(1, 2, 3);
    }

    /**
    * @depends testGetRegex
    */
    public function testRange_lowercase()
    {
        $lowercaseAlpha = new VerbalExpressions();
        $lowercaseAlpha->range('a', 'z')
            ->multiple('');

        $lowercaseAlpha_all = new VerbalExpressions();
        $lowercaseAlpha_all->startOfLine()
            ->range('a', 'z')
            ->multiple('')
            ->endOfLine();

        $this->assertEquals('/[a-z]+/m', $lowercaseAlpha->getRegex());
        $this->assertEquals('/^[a-z]+$/m', $lowercaseAlpha_all->getRegex());

        $this->assertTrue($lowercaseAlpha->test('a'));
        $this->assertFalse($lowercaseAlpha->test('A'));
        $this->assertTrue($lowercaseAlpha->test('alphabet'));
        $this->assertTrue($lowercaseAlpha->test('Alphabet'));
        $this->assertFalse($lowercaseAlpha_all->test('Alphabet'));
    }

    /**
    * @depends testGetRegex
    */
    public function testRange_uppercase()
    {
        $uppercaseAlpha = new VerbalExpressions();
        $uppercaseAlpha->range('A', 'Z')
            ->multiple('');

        $uppercaseAlpha_all = new VerbalExpressions();
        $uppercaseAlpha_all->startOfLine()
            ->range('A', 'Z')
            ->multiple('')
            ->endOfLine();

        $this->assertEquals('/[A-Z]+/m', $uppercaseAlpha->getRegex());
        $this->assertEquals('/^[A-Z]+$/m', $uppercaseAlpha_all->getRegex());

        $this->assertTrue($uppercaseAlpha->test('A'));
        $this->assertFalse($uppercaseAlpha->test('a'));
        $this->assertFalse($uppercaseAlpha->test('alphabet'));
        $this->assertTrue($uppercaseAlpha->test('Alphabet'));
        $this->assertTrue($uppercaseAlpha->test('ALPHABET'));
        $this->assertFalse($uppercaseAlpha_all->test('Alphabet'));
        $this->assertTrue($uppercaseAlpha_all->test('ALPHABET'));
    }

    /**
    * @depends testGetRegex
    */
    public function testRange_numerical()
    {
        $zeroToNine = new VerbalExpressions();
        $zeroToNine->range(0, 9)
            ->multiple('');

        $zeroToNine_all = new VerbalExpressions();
        $zeroToNine_all->startOfLine()
            ->range(0, 9)
            ->multiple('')
            ->endOfLine();

        $this->assertEquals('/[0-9]+/m', $zeroToNine->getRegex());
        $this->assertEquals('/^[0-9]+$/m', $zeroToNine_all->getRegex());

        $this->assertFalse($zeroToNine->test('alphabet'));
        $this->assertTrue($zeroToNine->test(0));
        $this->assertTrue($zeroToNine->test('0'));
        $this->assertTrue($zeroToNine->test(123));
        $this->assertTrue($zeroToNine->test('123'));
        $this->assertTrue($zeroToNine->test(1.23));
        $this->assertTrue($zeroToNine->test('1.23'));
        $this->assertFalse($zeroToNine_all->test(1.23));
        $this->assertFalse($zeroToNine_all->test('1.23'));
        $this->assertTrue($zeroToNine->test('£123'));
        $this->assertFalse($zeroToNine_all->test('£123'));
    }

    /**
    * @depends testGetRegex
    */
    public function testRange_hexadecimal()
    {
        $hexadecimal = new VerbalExpressions();
        $hexadecimal->startOfLine()
            ->range(0, 9, 'a', 'f')
            ->multiple('')
            ->endOfLine();

        $this->assertEquals('/^[0-9a-f]+$/m', $hexadecimal->getRegex());

        $this->assertFalse($hexadecimal->test('alphabet'));
        $this->assertTrue($hexadecimal->test('deadbeef'));
        $this->assertTrue($hexadecimal->test(md5('')));
        $this->assertTrue($hexadecimal->test(sha1('')));
    }

    /**
    * @depends testRange_hexadecimal
    */
    public function testRange_md5(){
        $md5 = new VerbalExpressions();
        $md5->startOfLine()
            ->range(0, 9, 'a', 'f')
            ->limit(32)
            ->endOfLine();

        $this->assertEquals('/^[0-9a-f]{32}$/m', $md5->getRegex());

        $this->assertFalse($md5->test('alphabet'));
        $this->assertFalse($md5->test('deadbeef'));
        $this->assertTrue($md5->test(md5('')));
        $this->assertFalse($md5->test(sha1('')));
    }

    /*
    * @depends testRange_hexadecimal
    */
    public function testRange_sha1(){
        $sha1 = new VerbalExpressions();
        $sha1->startOfLine()
            ->range(0, 9, 'a', 'f')
            ->limit(40)
            ->endOfLine();

        $this->assertEquals('/^[0-9a-f]{40}$/m', $sha1->getRegex());

        $this->assertFalse($sha1->test('alphabet'));
        $this->assertFalse($sha1->test('deadbeef'));
        $this->assertFalse($sha1->test(md5('')));
        $this->assertTrue($sha1->test(sha1('')));
    }
}

<?php

use VerbalExpressions\PHPVerbalExpressions\VerbalExpressions;

class VerbalExpressionsTest extends \PHPUnit_Framework_TestCase
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
    }

    public static function provideValidUrls()
    {
        return array(
            array('http://github.com'),
            array('http://www.github.com'),
            array('https://github.com'),
            array('https://github.com'),
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
}

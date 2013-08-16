<?php

use VerbalExpressions\PHPVerbalExpressions\VerbalExpressions;

class VerbalExpressionsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider provideUrls
     */
    public function testUrlPatterns($url)
    {
        $regex = new VerbalExpressions();

        $regex->startOfLine()
            ->then("http")
            ->maybe("s")
            ->then("://")
            ->maybe("www.")
            ->anythingBut(" ")
            ->endOfLine();

        $this->assertEquals(1, $regex->test($url));
    }

    static public function provideUrls()
    {
        return array(
            array('http://github.com')
        );
    }
}
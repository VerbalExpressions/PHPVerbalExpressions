<?php

namespace VerbalExpressions\Test\PHPVerbalExpressions\Parser;

use VerbalExpressions\PHPVerbalExpressions\Parser\MethodParser;
/**
 * MethodParser based on Verbal Expressions v0.1 (https://github.com/jehna/VerbalExpressions) ported in PHP
 *
 * @author Nicola Pietroluongo <nik.longstone@gmail.com>
 */
class MethodParserTest extends \PHPUnit_Framework_TestCase
{
    private $methodParser;

    public function setUp()
    {
        $this->methodParser = new MethodParser();
    }

    /**
     * @dataProvider methodsList
     */
    public function testParse($text, $expected)
    {
        $this->assertEquals($expected, $this->methodParser->parse($text));
    }

    public function methodsList()
    {
        return array(
            array('anythingBut', MethodParser::ANYTHING_BUT),
            array('anything But', MethodParser::ANYTHING_BUT),
            array('somethingBut', MethodParser::SOMETHING_BUT),
            array('something but', MethodParser::SOMETHING_BUT),
            array('something', MethodParser::SOMETHING),
            array('anything but', MethodParser::ANYTHING_BUT),
            array('anything', MethodParser::ANYTHING),
            array('end', MethodParser::END_OF_LINE),
            array('find', MethodParser::FIND),
            array('maybe', MethodParser::MAYBE),
            array('start', MethodParser::START_OF_LINE),
            array('then', MethodParser::THEN),
            array('any', MethodParser::ANY),
            array('anyof', MethodParser::ANY_OF),
            array('any of', MethodParser::ANY_OF),
            array('br', MethodParser::BR),
            array('linebreak', MethodParser::LINE_BREAK),
            array('line break', MethodParser::LINE_BREAK),
            array('range', MethodParser::RANGE),
            array('tab', MethodParser::TAB),
            array('word', MethodParser::WORD),
            array('withanycase', MethodParser::WITH_ANY_CASE),
            array('with any case', MethodParser::WITH_ANY_CASE),
            array('any case', MethodParser::WITH_ANY_CASE),
            array('stopatfirst', MethodParser::STOP_AT_FIRST),
            array('stop at first', MethodParser::STOP_AT_FIRST),
            array('stop first', MethodParser::STOP_AT_FIRST),
            array('searchoneline', MethodParser::SEARCH_ONE_LINE),
            array('search one line', MethodParser::SEARCH_ONE_LINE),
            array('add modifier', MethodParser::ADD_MODIFIER),
            array('remove modifier', MethodParser::REMOVE_MODIFIER),
            array('replace', MethodParser::REPLACE),
            array('add', MethodParser::ADD),
            array('limit', MethodParser::LIMIT),
            array('multiple', MethodParser::MULTIPLE),
            array('or', MethodParser::_OR)
        );
    }
}
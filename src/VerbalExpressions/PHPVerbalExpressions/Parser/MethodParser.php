<?php

namespace VerbalExpressions\PHPVerbalExpressions\Parser;

/**
 * MethodParser.
 *
 * Provides VerbalExpression method mapping for business language expression definition.
 *
 * @author Nicola Pietroluongo <nik.longstone@gmail.com>
 */
class MethodParser implements MethodParserInterface
{
    const ANYTHING = 'anything';
    const ANYTHING_BUT = 'anythingBut';
    const SOMETHING = 'something';
    const SOMETHING_BUT = 'somethingBut';
    const END_OF_LINE = 'endOfLine';
    const FIND = 'find';
    const MAYBE = 'maybe';
    const START_OF_LINE = 'startOfLine';
    const THEN = 'then';
    const ANY = 'any';
    const ANY_OF = 'anyOf';
    const BR = 'br';
    const LINE_BREAK = 'lineBreak';
    const RANGE = 'rangeBridge';
    const TAB = 'tab';
    const WORD = 'word';
    const WITH_ANY_CASE = 'withAnyCase';
    const STOP_AT_FIRST = 'stopAtFirst';
    const SEARCH_ONE_LINE = 'searchOneLine';
    const REPLACE = 'replaceBridge';
    const ADD = 'add';
    const LIMIT = 'limit';
    const MULTIPLE = 'multiple';
    const ADD_MODIFIER = 'addModifier';
    const REMOVE_MODIFIER = 'removeModifier';
    const _OR = '_or';

    /**
     * @param string $scenarioString
     * @return null
     */
    public function parse($scenarioString)
    {
        $definitions = $this->getDefinitions();
        foreach ($definitions as $key => $val) {
            if (preg_match('/'.$key.'/i', $scenarioString)) {
                return $val;
            }
        }

        return null;
    }

    /**
     * @return array
     */
    private function getDefinitions()
    {
        return array(
            'anythingbut' => self::ANYTHING_BUT,
            'somethingbut' => self::SOMETHING_BUT,
            'something but' => self::SOMETHING_BUT,
            'something' => self::SOMETHING,
            'anything but' => self::ANYTHING_BUT,
            'anything' => self::ANYTHING,
            'end' => self::END_OF_LINE,
            'end of line' => self::END_OF_LINE,
            'endofline' => self::END_OF_LINE,
            'find' => self::FIND,
            'maybe' => self::MAYBE,
            'start' => self::START_OF_LINE,
            'startofline' => self::START_OF_LINE,
            'start of line' => self::START_OF_LINE,
            'then' => self::THEN,
            'anyof' => self::ANY_OF,
            'any of' => self::ANY_OF,
            'linebreak' => self::LINE_BREAK,
            'line break' => self::LINE_BREAK,
            'br' => self::BR,
            'range' => self::RANGE,
            'tab' => self::TAB,
            'word' => self::WORD,
            'withanycase' => self::WITH_ANY_CASE,
            'with any case' => self::WITH_ANY_CASE,
            'any case' => self::WITH_ANY_CASE,
            'any' => self::ANY,
            'stopatfirst' => self::STOP_AT_FIRST,
            'stop at first' => self::STOP_AT_FIRST,
            'stop first' => self::STOP_AT_FIRST,
            'searchoneline' => self::SEARCH_ONE_LINE,
            'search one line' => self::SEARCH_ONE_LINE,
            'replace' => self::REPLACE,
            'add modifier' => self::ADD_MODIFIER,
            'addmodifier' => self::ADD_MODIFIER,
            'add' => self::ADD,
            'remove modifier' => self::REMOVE_MODIFIER,
            'removemodifier' => self::REMOVE_MODIFIER,
            'limit' => self::LIMIT,
            'multiple' => self::MULTIPLE,
            'or' => self::_OR
        );
    }
}
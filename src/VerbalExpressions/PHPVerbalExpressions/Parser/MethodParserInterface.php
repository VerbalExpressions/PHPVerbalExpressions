<?php

namespace VerbalExpressions\PHPVerbalExpressions\Parser;

/**
 * MethodParserInterface.
 *
 * Provides an interface for mapping methods
 * and support business language expression definition.
 *
 * @author Nicola Pietroluongo <nik.longstone@gmail.com>
 */
interface MethodParserInterface
{
    /**
     * Parses a single declaration feature
     * and return the relative VerbalExpression method name.
     *
     * @param $singleFeatures
     *
     * @return string|null
     */
    public function parse($singleFeatures);
}
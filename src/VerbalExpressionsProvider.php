<?php

namespace VerbalExpressions\PHPVerbalExpressions;

/**
 * Returns the best avaible implementation for the given version of PHP
 *
 * @author Björn Büttner
 */
class VerbalExpressionsProvider
{
    /**
     * Returns the best avaible implementation for the given version of PHP
     * @return OldVerbalExpressions|VerbalExpressions
     */
    public static function get() {
        if(!defined('HHVM_VERSION') && version_compare(phpversion(),'5.6.0', '<')) {
            return new OldVerbalExpressions();
        }
        return new VerbalExpressions();
    }
}
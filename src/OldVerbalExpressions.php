<?php

namespace VerbalExpressions\PHPVerbalExpressions;

/**
 * Verbal Expressions v0.1 (https://github.com/jehna/VerbalExpressions) ported in PHP
 * @author Mihai Ionut Vilcu (ionutvmi@gmail.com)
 * 22.July.2013
 */

class OldVerbalExpressions extends AbstractVerbalExpressions
{
    /**
     * Add a range
     * this works with php 5.3-5.5
     * Adds a range to our expression ex: range(a,z) => a-z, range(a,z,0,9) => a-z0-9
     *
     * @access public
     * @return VerbalExpressions
     * @throws \InvalidArgumentException
     */
    public function range()
    {
        return parent::addRange(func_get_args());
    }
}

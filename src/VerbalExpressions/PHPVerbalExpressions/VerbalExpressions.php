<?php

namespace VerbalExpressions\PHPVerbalExpressions;

/**
 * Verbal Expressions v0.1 (https://github.com/jehna/VerbalExpressions) ported in PHP
 * @author Mihai Ionut Vilcu (ionutvmi@gmail.com)
 * 22.July.2013
 */

class VerbalExpressions
{
    public $prefixes     = "";
    public $source       = "";
    public $suffixes     = "";
    public $modifiers    = "m"; // default to global multi line matching
    public $replaceLimit = 1;   // the limit of preg_replace when g modifier is not set
    protected $lastAdded = false; // holds the last added regex 

    /**
     * Sanitize
     *
     * Sanitation function for adding anything safely to the expression
     *
     * @access public
     * @param  string $value the to be added
     * @return string escaped value
     */
    public static function sanitize($value)
    {
        return $value ? preg_quote($value, "/") : $value;
    }

    /**
     * Add
     *
     * Add stuff to the expression
     *
     * @access public
     * @param  string $value the stuff to be added
     * @return VerbalExpressions
     */
    public function add($value)
    {
        $this->source .= $this->lastAdded = $value;

        return $this;
    }

    /**
     * Start of Line
     *
     * Mark the expression to start at the beginning of the line.
     *
     * @access public
     * @param  boolean $enable Enables or disables the line starting. Default value: true
     * @return VerbalExpressions
     */
    public function startOfLine($enable = true)
    {
        $this->prefixes = $enable ? "^" : "";

        return $this;
    }

    /**
     * End of line
     *
     * Mark the expression to end at the last character of the line.
     *
     * @access public
     * @param  boolean $enable Enables or disables the line ending. Default value: true
     * @return VerbalExpressions
     */
    public function endOfLine($enable = true)
    {
        $this->suffixes = $enable ? "$" : "";

        return $this;
    }

    /**
     * Add
     *
     * Add a string to the expression
     *
     * @access public
     * @param  string $value The string to be looked for
     * @return VerbalExpressions
     */
    public function then($value)
    {
        return $this->add("(?:".self::sanitize($value).")");
    }

    /**
     * alias for then()
     * @param  string $value The string to be looked for
     * @return VerbalExpressions
     */
    public function find($value)
    {
        return $this->then($value);
    }

    /**
     * Maybe
     *
     *  Add a string to the expression that might appear once (or not).
     *
     * @access public
     * @param  string $value The string to be looked for
     * @return VerbalExpressions
     */
    public function maybe($value)
    {
        return $this->add("(?:".self::sanitize($value).")?");
    }

    /**
     * Anything
     *
     * Accept any string
     *
     * @access public
     * @return VerbalExpressions
     */
    public function anything()
    {
        return $this->add("(?:.*)");
    }

    /**
     * AnythingBut
     *
     * Anything but this chars
     *
     * @access public
     * @param  string $value The unaccepted chars
     * @return VerbalExpressions
     */
    public function anythingBut($value)
    {
        return $this->add("(?:[^". self::sanitize($value) ."]*)");
    }

    /**
     * Something
     *
     * Accept any non-empty string
     *
     * @access public
     * @return VerbalExpressions
     */
    public function something()
    {
        return $this->add("(?:.+)");
    }

    /**
     * Anything but
     *
     * Anything non-empty except for these chars
     *
     * @access public
     * @param  string $value The unaccepted chars
     * @return VerbalExpressions
     */
    public function somethingBut($value)
    {
        return $this->add("(?:[^". self::sanitize($value) ."]+)");
    }

    /**
     * Preg Replace
     *
     * Shorthand for preg_replace()
     *
     * @access public
     * @param  string $source the string that will be affected(subject)
     * @param  string $value  the replacement
     * @return VerbalExpressions
     */
    public function replace($source, $value)
    {
        // php doesn't have g modifier so we remove it if it's there and we remove limit param
        if (strpos($this->modifiers, 'g') !== false) {
            $this->modifiers = str_replace('g', '', $this->modifiers);

            return preg_replace($this->getRegex(), $value, $source);
        }

        return preg_replace($this->getRegex(), $value, $source, $this->replaceLimit);
    }

    /**
     * Line break
     *
     * Match line break
     *
     * @access public
     * @return VerbalExpressions
     */
    public function lineBreak()
    {
        return $this->add("(?:\\n|(\\r\\n))");
    }

    /**
     * Line break
     *
     * Shorthand for lineBreak
     *
     * @access public
     * return VerbalExpressions
     */
    public function br()
    {
        return $this->lineBreak();
    }

    /**
     * Tabs
     *
     * Match tabs.
     *
     * @access public
     * @return VerbalExpressions
     */
    public function tab()
    {
        return $this->add("\\t");
    }

    /**
     * Alpha Numeric
     *
     * Match any alpha numeric
     *
     * @access public
     * @return VerbalExpressions
     */
    public function word()
    {
        return $this->add("\\w+");
    }

    /**
     * List Chars
     *
     * Any of the listed chars
     *
     * @access public
     * @param  string $value The chars looked for
     * @return VerbalExpressions
     */
    public function anyOf($value)
    {
        return $this->add("[". $value ."]");
    }

    /**
     * Alias
     *
     * Shorthand for anyOf
     *
     * @access public
     * @param  string $value The chars looked for
     * @return VerbalExpressions
     */
    public function any($value)
    {
        return $this->anyOf($value);
    }

    /**
     * Add a range
     *
     * Adds a range to our expression ex: range(a,z) => a-z, range(a,z,0,9) => a-z0-9
     *
     * @access public
     * @return VerbalExpressions
     * @throws \InvalidArgumentException
     */
    public function range()
    {

        $arg_num = func_num_args();

        if ($arg_num%2 != 0) {
            throw new \InvalidArgumentException("Number of args must be even", 1);
        }

        $value = "[";
        $arg_list = func_get_args();

        for ($i = 0; $i < $arg_num;) {
            $value .= self::sanitize($arg_list[$i++]) . "-" . self::sanitize($arg_list[$i++]);
        }

        $value .= "]";

        return $this->add($value);
    }

    /**
     * Add a modifier
     *
     * Adds a modifier
     *
     * @access public
     * @param  string $modifier
     * @return VerbalExpressions
     */
    public function addModifier($modifier)
    {
        if (strpos($this->modifiers, $modifier) === false) {
            $this->modifiers .= $modifier;
        }

        return $this;
    }

    /**
     * Remove Modifier
     *
     * Removes a modifier
     *
     * @access public
     * @param  string $modifier
     * @return VerbalExpressions
     */
    public function removeModifier($modifier)
    {
        $this->modifiers = str_replace($modifier, '', $modifier);

        return $this;
    }

    /**
     * Case Sensitivity
     *
     * Match case insensitive or sensitive based on $enable value
     *
     * @access public
     * @param  boolean $enable Enables or disables case sensitive. Default true
     * @return VerbalExpressions
     */
    public function withAnyCase($enable = true)
    {
        return $enable ? $this->addModifier('i') : $this->removeModifier('i');
    }

    /**
     * Stop At First
     *
     * Toggles g modifier
     *
     * @access public
     * @param  boolean $enable Enables or disables g modifier. Default true
     * @return VerbalExpressions
     */
    public function stopAtFirst($enable = true)
    {
        return $enable ? $this->addModifier('g') : $this->removeModifier('g');
    }

    /**
     * SearchOneLine
     *
     * Toggles m modifier
     *
     * @access public
     * @param  boolean $enable Enables or disables m modifier. Default true
     * @return VerbalExpressions
     */
    public function searchOneLine($enable = true)
    {
        return $enable ? $this->addModifier('m') : $this->removeModifier('m');
    }

    /**
     * Multiple
     *
     * Adds the multiple modifier at the end of your expression
     *
     * @access public
     * @param  string $value Your expression
     * @return VerbalExpressions
     */
    public function multiple($value)
    {
        $value = self::sanitize($value);

        switch (substr($value, -1)) {
            case '+':
            case '*':
                break;

            default:
                $value .= '+';
                break;
        }

        return $this->add($value);
    }

    /**
     * OR
     *
     * Wraps the current expression in an `or` with $value
     *
     * @access public
     * @param  string $value new expression
     * @return VerbalExpressions
     */
    public function _or($value)
    {
        if (strpos($this->prefixes,"(")===false) {
            $this->prefixes .= "(?:";
        }

        if (strpos($this->suffixes, ")")===false) {
            $this->suffixes .= ")";
        }

        $this->add(")|(?:");

        if ($value) {
            $this->add($value);
        }

        return $this;
    }

    /**
     * Object to string
     *
     * PHP Magic method to return a string representation of the object.
     *
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->getRegex();
    }

    /**
     * Get the Expression
     *
     * Creates the final regex.
     *
     * @access public
     * @return string The final regex
     */
    public function getRegex()
    {
        return "/".$this->prefixes.$this->source.$this->suffixes."/".$this->modifiers;
    }

    /**
     * Test
     *
     * tests the match of a string to the current regex
     *
     * @access public
     * @param  string  $value The string to be tested
     * @return boolean true if it's a match
     */
    public function test($value)
    {
        // php doesn't have g modifier so we remove it if it's there and call preg_match_all()
        if (strpos($this->modifiers, 'g') !== false) {
            $this->modifiers = str_replace('g', '', $this->modifiers);

            return preg_match_all($this->getRegex(), $value);
        }

        return (bool) preg_match($this->getRegex(), $value);
    }

    /**
     * Clean
     *
     * deletes the current regex for a fresh start
     *
     * @access public
     * @param  array $options
     * @return VerbalExpressions
     */
    public function clean($options = array())
    {
        $options            = array_merge(array("prefixes"=> "", "source"=>"", "suffixes"=>"", "modifiers"=>"gm","replaceLimit"=>"1"), $options);
        $this->prefixes     = $options['prefixes'];
        $this->source       = $options['source'];
        $this->suffixes     = $options['suffixes'];
        $this->modifiers    = $options['modifiers'];    // default to global multi line matching
        $this->replaceLimit = $options['replaceLimit']; // default to global multi line matching

        return $this;
    }

    /**
     * Limit
     * 
     * Adds char limit to the last added expression. 
     * If $max is less then $min the limit will be: At least $min chars {$min,}
     * If $max is 0 the limit will be: exactly $min chars {$min}
     * If $max bigger then $min the limit will be: at least $min but not more then $max {$min, $max}
     * 
     * @access public
     * @param integer $min
     * @param integer $max
     * @return VerbalExpressions
     */
    public function limit($min, $max = 0) {
        if($max == 0)
            $value = "{".$min."}";
        
        else if($max < $min)
            $value = "{".$min.",}";
        
        else
            $value = "{".$min.",".$max."}";

        // check if the expression has * or + for the last expression
        if(preg_match("/\*|\+/", $this->lastAdded)) {
            $l = 1;
            $this->source = strrev(str_replace(array('+','*'), strrev($value), strrev($this->source), $l));
            return $this;
        }

        return $this->add($value);
            
    }

}

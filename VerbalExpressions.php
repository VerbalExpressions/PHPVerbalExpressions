<?php
/**
 * Verbal Expressions v0.1 (https://github.com/jehna/VerbalExpressions) ported in PHP
 * @author Mihai Ionut Vilcu (ionutvmi@gmail.com)
 * 22.July.2013
 */


// // some tests

// $regex = new VerEx;

// $regex 	->startOfLine()
// 		->then( "http" )
// 		->maybe( "s" )
// 		->then( "://" )
// 		->maybe( "www." )
// 		->anythingBut( " " )
// 		->endOfLine();


// if($regex->test("http://github.com"))
// 	echo "valid url";
// else
// 	echo "invalid url";

// if (preg_match($regex, 'http://github.com')) {
// 	echo 'valid url';
// } else {
// 	echo 'invalud url';
// }

// echo "<pre>". $regex->getRegex() ."</pre>";


// echo $regex ->clean(array("modifiers"=> "m","replaceLimit"=>4))
// 			->find(' ')
// 			->replace("This is a small test http://somesite.com and some more text.", "-");


class VerEx {

	public $prefixes     = "";
	public $source       = "";
	public $suffixes     = "";
	public $modifiers    = "m"; // default to global multiline matching
	public $replaceLimit = 1;   // the limit of preg_replace when g modifier is not set

	/**
	 * Sanitize
	 *
	 * Sanitation function for adding anything safely to the expression
	 *
	 * @access public
	 * @param  string $value the to be added
	 * @return string        escaped value
	 */
	public function sanitize($value) 
	{
		if(!$value) 
		{
			return $value;
		}
		return preg_quote($value, "/");
	}

	/**
	 * Add
	 *
	 * Add stuff to the expression 
	 *
	 * @access public
	 * @param string $value the stuff to be added
	 * @return VerEx
	 */
	public function add($value) 
	{
		$this->source .= $value;
		return $this;
	}

	/**
	 * Start of Line
	 *
	 * Mark the expression to start at the beginning of the line.
	 *
	 * @access public
	 * @param  boolean $enable Enables or disables the line starting. Default value: true
	 * @return VerEx
	 */
	public function startOfLine($enable=true)
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
	 * @return VerEx
	 */
	public function endOfLine($enable=true) 
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
	 * @return VerEx
	 */
	public function then($value) 
	{
		$this->add("(".$this->sanitize($value).")");
		return $this;
	}

	/**
	 * alias for then()
	 * @param  string $value The string to be looked for
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
	 * @return VerEx
	 */
	public function maybe($value) 
	{
		$this->add("(".$this->sanitize($value).")?");
		return $this;
	}

	/**
	 * Anything
	 *
	 * Accept any string
	 *
	 * @access public
	 * @return VerEx 
	 */
	public function anything() 
	{
		$this->add("(.*)");
		return $this;
	}

	/**
	 * Anthing But
	 *
	 * Anything but this chars
	 *
	 * @access public
	 * @param  string $value The unaccepted chars
	 * @return VerEx
	 */
	public function anythingBut( $value ) 
	{
		$this->add("([^". $this->sanitize($value) ."]*)");
		return $this;
	}

	/**
	 * Something
	 *
	 * Accept any non-empty string 
	 *
	 * @access public
	 * @return VerEx
	 */
	public function something() 
	{
		$this->add("(.+)");
		return $this;
	}

	/**
	 * Anything but
	 *
	 * Anything non-empty except for these chars
	 *
	 * @access public
	 * @param  string $value The unaccepted chars
	 * @return VerEx
	 */
	public function somethingBut($value) 
	{
		$this->add("([^". $this->sanitize($value) ."]+)");
		return $this;
	}

	/**
	 * Preg Replace
	 *
	 * Shorthand for preg_replace()
	 *
	 * @access public
	 * @param  string $source the string that will be affected(subject)
	 * @param  string $value  the replacement
	 * @return VerEx
	 */
	public function replace($source, $value) 
	{
		// php doesn't have g modifier so we remove it if it's there and we remove limit param
		if(strpos($this->modifiers, 'g') !== false)
		{
			$this->modifiers = str_replace('g', '', $this->modifiers);
			return preg_replace($this->getRegex(), $value, $source);
		}		

		return preg_replace($this->getRegex(), $value, $source, $this->replaceLimit);
	}

	/**
	 * Linebreak
	 *
	 * Match line break
	 *
	 * @access public
	 * @return VerEx
	 */
	public function lineBreak() 
	{
		$this->add("(\\n|(\\r\\n))");
		return $this;
	}

	/**
	 * Linebreak
	 *
	 * Shorthand for lineBreak
	 *
	 * @access public
	 * return object
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
	 * @return VerEx
	 */
	public function tab() 
	{
		$this->add("\\t");
		return $this;
	}

	/**
	 * Alpha Numberic
	 *
	 * Match any alfanumeric
	 *
	 * @access public
	 * @return VerEx
	 */
	public function word() 
	{
		$this->add("\\w+");
		return $this;
	}

	/**
	 * List Chars
	 *
	 * Any of the listed chars
	 *
	 * @access public
	 * @param  string $value The chars looked for
	 * @return VerEx
	 */
	public function anyOf($value) 
	{
		$this->add("[". $value ."]");
		return $this;
	}

	/**
	 * Alias
	 *
	 * Shorthand for anyOf
	 *
	 * @access public
	 * @param  string $value The chars looked for
	 * @return VerEx
	 */
	public function any($value) 
	{
		return $this->anyOf($value);
	}

	/**
	 * Add a range
	 *
	 * Adds a range to our expresion ex: range(a,z) => a-z, range(a,z,0,9) => a-z0-9
	 *
	 * @access public
	 * @return VerEx
	 */
	public function range() 
	{

		$arg_num = func_num_args();

		if($arg_num%2 != 0)
		{
			throw new Exception("Number of args must be even", 1);
		}

		$value = "[";
		$arg_list = func_get_args();

		for($i = 0; $i < $arg_num;)
		{
			$value .= $this->sanitize($arg_list[$i++]) . " - " . $this->sanitize($arg_list[$i++]);
		}

		$value .= "]";

		$this->add($value);

		return $this;
	}

	/**
	 * Add a modifier
	 *
	 * Adds a modifier
	 * 
	 * @access public
	 * @param str $modifier
	 * @return VerEx
	 */
	public function addModifier($modifier)
	{
		if(strpos($this->modifiers, $modifier) === false)
		{
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
	 * @param str $modifier
	 * @return VerEx
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
	 * @return VerEx
	 */
	public function withAnyCase($enable=true)
	{
		if($enable)
		{
			$this->addModifier('i');
		}
		else
		{
			$this->removeModifier('i');
		}

		return $this;
	}

	/**
	 * Stop At First
	 *
	 * Toggles g modifier
	 *
	 * @access public
	 * @param  boolean $enable Enables or disables g modifier. Default true
	 * @return VerEx
	 */
	public function stopAtFirst($enable=true) 
	{
		if($enable)
		{
			$this->addModifier('g');
		}
		else
		{
			$this->removeModifier('g');
		}

		return $this;
	}

	/**
	 * SearchOneline
	 *
	 * Toggles m modifier
	 *
	 * @access public
	 * @param  boolean $enable Enables or disables m modifier. Default true
	 * @return VerEx
	 */
	public function searchOneLine($enable=true) 
	{
		if($enable===true)
		{
			$this->addModifier('m');
		}
		else
		{
			$this->removeModifier('m');
		}

		return $this;
	}

	/**
	 * Multiple
	 *
	 * Adds the multiple modifier at the end of your expresion
	 *
	 * @access public
	 * @param  string $value Your expresion
	 * @return VerEx
	 */
	public function multiple($value) 
	{
		$value = $this->sanitize($value);

		switch (substr($value, -1))
		{
			case '+':
			case '*':
				break;
			
			default:
				$value += '+';
				break;
		}

		$this->add($value);

		return $this;
	}

	/**
	 * OR
	 *
	 * Wraps the current expresion in an `or` with $value
	 *
	 * @access public
	 * @param  string $value new expression
	 * @return VerEx
	 */
	public function _or($value)
	{
		if(strpos($this->prefixes,"(")===false)
		{
			$this->prefixes .= "(";
		}

		if(strpos($this->suffixes, ")")===false)
		{
			$this->suffixes .= ")";
		}

		$this->add(")|(");

		if($value)
		{
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
	 * @param  string $value The string to be tested
	 * @return boolean        true if it's a match
	 */
	public function test($value)
	{
		// php doesn't have g modifier so we remove it if it's there and call preg_match_all()
		if(strpos($this->modifiers, 'g') !== false)
		{
			$this->modifiers = str_replace('g', '', $this->modifiers);

			return preg_match_all($this->getRegex(), $value);
		}

		return preg_match($this->getRegex(), $value);
	}

	/**
	 * Clean
	 * 
	 * deletes the current regex for a fresh start
	 *
	 * @access public
	 * @param array $options
	 * @return VerEx
	 */
	public function clean($options = array()) 
	{
		$options            = array_merge(array("prefixes"=> "", "source"=>"", "suffixes"=>"", "modifiers"=>"gm","replaceLimit"=>"1"), $options);
		$this->prefixes     = $options['prefixes'];
		$this->source       = $options['source'];
		$this->suffixes     = $options['suffixes'];
		$this->modifiers    = $options['modifiers'];    // default to global multiline matching
		$this->replaceLimit = $options['replaceLimit']; // default to global multiline matching

		return $this;
	}

}

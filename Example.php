<?php
/**
 * Verbal Expressions v0.1 (https://github.com/jehna/VerbalExpressions) ported in PHP
 * @author Mihai Ionut Vilcu (ionutvmi@gmail.com)
 * 22.July.2013
 */

$loader = require __DIR__ . '/vendor/autoload.php';

$regex = new \VerbalExpressions\PHPVerbalExpressions\VerbalExpressions();

$regex->startOfLine()
	  ->then("http")
	  ->maybe("s")
	  ->then("://")
	  ->maybe("www.")
	  ->anythingBut(" ")
	  ->endOfLine();

if($regex->test("http://github.com"))
	echo "valid url". '<br>';
else
	echo "invalid url". '<br>';

if (preg_match($regex, 'http://github.com')) {
	echo 'valid url';
} else {
	echo 'invalud url';
}

echo "<pre>". $regex->getRegex() ."</pre>";

echo $regex->clean(array("modifiers" => "m", "replaceLimit" => 4))
		   ->find(' ')
		   ->replace("This is a small test http://somesite.com and some more text.", "-");


echo "<hr>";

// limit
echo $regex ->clean(array("modifiers" => "m"))
			->startOfLine()
			->anyOf("abc")
			->anythingBut(" ")
			->limit(1)
			->withAnyCase()
			->replace("Abracadabra is a nice word", "Ba");

echo "<br>";



echo $regex ->clean(array("modifiers" => "gm"))
			->add("\b")
			->word()
			->limit(2, 3)
			->add("\b")
			->replace("test abc ab abcd", "*");









[![Build Status](https://travis-ci.org/VerbalExpressions/PHPVerbalExpressions.svg)](https://travis-ci.org/VerbalExpressions/PHPVerbalExpressions)

## PHPVerbalExpressions
- ported from [VerbalExpressions](https://github.com/VerbalExpressions/JSVerbalExpressions)

VerbalExpressions is a PHP library that helps to construct hard regular expressions.  

## Installation
The project supports Composer so you have to install [Composer](http://getcomposer.org/doc/00-intro.md#installation-nix) first, before project setup.

```sh
$ composer require  verbalexpressions/php-verbal-expressions:dev-master
```

## Examples

```php
<?php
// some tests
require './vendor/autoload.php';
use VerbalExpressions\PHPVerbalExpressions\VerbalExpressions;

$regex = new VerbalExpressions();

$regex->startOfLine()
      ->then("http")
      ->maybe("s")
      ->then("://")
      ->maybe("www.")
      ->anythingBut(" ")
      ->endOfLine();


if ($regex->test("http://github.com")) {
    echo "valid url". '<br>';
} else {
    echo "invalid url". '<br>';
}

if (preg_match($regex, 'http://github.com')) {
    echo 'valid url';
} else {
    echo 'invalid url';
}


echo "<pre>". $regex->getRegex() ."</pre>";

echo $regex->clean(array("modifiers" => "m", "replaceLimit" => 4))
           ->find(' ')
           ->replace("This is a small test http://somesite.com and some more text.", "-");

```

More examples are available in the following files:

- [Example.php](Example.php)
- [VerbalExpressionsTest.php](tests/VerbalExpressionsTest.php)

### Business readable language  expression definition
```PHP
$definition = 'start, then "http", maybe "s", then "://", maybe "www.", anything but " ", end';
$regex = new VerbalExpressionsScenario($definition);
```

## Methods list

Name|Description|Usage
:---|:---|:---
add| add values to the expression| add('abc')
startOfLine| mark expression with ^| startOfLine(false)
endOfLine| mark the expression with $|endOfLine()
then|add a string to the expression| add('foo')
find| alias for then| find('foo')
maybe| define a string that might appear once or not| maybe('.com')
anything| accept any string| anything()
anythingBut| accept any string but the specified char| anythingBut(',')
something| accept any non-empty string| something()
somethingBut| anything non-empty except for these chars| somethingBut('a')
replace| shorthand for preg_replace()| replace($source, $val)
lineBreak| match \r \n|lineBreak()
br|shorthand for lineBreak| br()
tab|match tabs \t |tab()
word|match \w+|word()
anyOf| any of the listed chars| anyOf('abc')
any| shorthand for anyOf| any('abc')
range| adds a range to the expression|range(a,z,0,9)
withAnyCase| match case default case sensitive|withAnyCase()
stopAtFirst|toggles the g modifiers|stopAtFirst()
addModifier| add a modifier|addModifier('g')
removeModifier| remove a mofier|removeModifier('g')
searchOneLine| Toggles m modifier|searchOneLine()
multiple|adds the multiple modifier| multiple('*')
_or|wraps the expression in an `or` with the provided value|_or('bar')
limit|adds char limit|limit(1,3)
test| performs a preg_match| test('valid@email.com')

For all the above method (except `test`) you could use the `VerbalExpressionsScenario`.

## Other Implementations
You can see an up to date list of all ports on [VerbalExpressions.github.io](http://VerbalExpressions.github.io).
- [Javascript](https://github.com/jehna/VerbalExpressions)
- [Ruby](https://github.com/VerbalExpressions/RubyVerbalExpressions)
- [C#](https://github.com/VerbalExpressions/CSharpVerbalExpressions)
- [Python](https://github.com/VerbalExpressions/PythonVerbalExpressions)
- [Java](https://github.com/VerbalExpressions/JavaVerbalExpressions)
- [C++](https://github.com/VerbalExpressions/CppVerbalExpressions)

## Building the project and running the tests
The project supports Composer so you have to install [Composer](https://getcomposer.org/doc/00-intro.md#installation-nix) first before project setup.

    curl -sS https://getcomposer.org/installer | php
    php composer.phar install --dev
    ln -s vendor/phpunit/phpunit/phpunit.php phpunit
    ./phpunit


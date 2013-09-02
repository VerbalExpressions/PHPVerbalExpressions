##PHPVerbalExpressions 
- ported from [VerbalExpressions](https://github.com/jehna/VerbalExpressions)

VerbalExpressions is a PHP library that helps to construct hard regular expressions.  


```php

// some tests

$regex = new VerbalExpressions;

$regex  ->startOfLine()
        ->then("http")
        ->maybe("s")
        ->then("://")
        ->maybe("www.")
        ->anythingBut(" ")
        ->endOfLine();


if($regex->test("http://github.com"))
    echo "valid url";
else
    echo "invalid url";

if (preg_match($regex, 'http://github.com')) {
    echo 'valid url';
} else {
    echo 'invalud url';
}


echo "<pre>". $regex->getRegex() ."</pre>";



echo $regex ->clean(array("modifiers" => "m", "replaceLimit" => 4))
            ->find(' ')
            ->replace("This is a small test http://somesite.com and some more text.", "-");

```

## Other Implementations
You can see an up to date list of all ports on [VerbalExpressions.github.io](http://VerbalExpressions.github.io).
- [Javascript](https://github.com/jehna/VerbalExpressions)
- [Ruby](https://github.com/VerbalExpressions/RubyVerbalExpressions)
- [C#](https://github.com/VerbalExpressions/CSharpVerbalExpressions)
- [Python](https://github.com/VerbalExpressions/PythonVerbalExpressions)
- [Java](https://github.com/VerbalExpressions/JavaVerbalExpressions)
- [C++](https://github.com/VerbalExpressions/CppVerbalExpressions)

## Building the project and running the tests
The project supports Composer so you have to install [Composer](http://getcomposer.org/doc/00-intro.md#installation-nix) first before project setup.

    curl -sS https://getcomposer.org/installer | php
    php composer.phar install --dev
    ln -s vendor/phpunit/phpunit/phpunit.php phpunit
    ./phpunit
    


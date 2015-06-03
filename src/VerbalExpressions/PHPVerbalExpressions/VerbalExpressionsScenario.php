<?php

namespace VerbalExpressions\PHPVerbalExpressions;

use VerbalExpressions\PHPVerbalExpressions\Entity\Expression;
use VerbalExpressions\PHPVerbalExpressions\Parser\MethodParser;

/**
 * VerbalExpressionsScenario,
 * based on Verbal Expressions v0.1 (https://github.com/jehna/VerbalExpressions) ported in PHP.
 *
 * Provides a business readable language support for expressions definition.
 *
 * @author Nicola Pietroluongo <nik.longstone@gmail.com>
 */
class VerbalExpressionsScenario extends VerbalExpressions
{

    /**
     * @var string
     */
    private $methodDelimiter;

    /**
     * @var string
     */
    private $argumentDelimiter;

    /**
     * @var MethodParser
     */
    private $methodParser;

    /**
     * @param string                        $scenario
     * @param string                        $methodDelimiter
     * @param string                        $argumentDelimiter
     * @param null|MethodParserInterface    $methodParser
     *
     * @return VerbalExpressionsScenario
     */
    public function __construct($scenario, $methodDelimiter = ', ', $argumentDelimiter = '"', $methodParser = null)
    {
        if (null == $methodParser || !$methodParser instanceof MethodParserInterface) {
            $this->methodParser = new MethodParser();
        }
        $this->methodDelimiter = $methodDelimiter;
        $this->argumentDelimiter = $argumentDelimiter;
        $parsedScenario = $this->parseScenario($scenario);

        return $this->runScenario($parsedScenario);
    }

    /**
     * @param string $scenarioString
     *
     * @return array
     */
    private function parseScenario($scenarioString)
    {
        $parsedScenario = array();
        $scenarios = explode($this->methodDelimiter, $scenarioString);
        foreach ($scenarios as $scenario) {
            $arguments = null;
            $scenarioElement = explode($this->argumentDelimiter, $scenario);
            if (isset($scenarioElement[1])) {
                $arguments = $scenarioElement[1];
            }
            $parsedScenario[] = new Expression($this->methodParser->parse($scenarioElement[0]), $arguments);
        }

        return $parsedScenario;
    }

    /**
     * @param array $parsedScenario
     *
     * @return VerbalExpressions
     */
    private function runScenario($parsedScenario)
    {
        foreach ($parsedScenario as $expression) {
            call_user_func_array(array($this, $expression->getName()), array($expression->getArguments()));
        }

        return $this;
    }

    /**
     * @param null $range
     *
     * @return VerbalExpressions
     * @throws \InvalidArgumentException
     */
    public function rangeBridge($range = null)
    {
        $range = explode(",", $range);
        call_user_func_array(array($this, 'range'), $range);
    }

    /**
     * @param array $arguments
     */
    public function replaceBridge($arguments)
    {
        call_user_func(array($this, 'replace'), $arguments[0], $arguments[1]);
    }
}

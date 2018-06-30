<?php

namespace VerbalExpressions\PHPVerbalExpressions\Entity;

/**
 * Expression,
 * defines a single Expression entity.
 *
 * @author Nicola Pietroluongo <nik.longstone@gmail.com>
 */
class Expression
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var mixed
     */
    private $arguments;

    /**
     * @param string $name
     * @param null   $arguments
     */
    public function __construct($name, $arguments = null)
    {
        $this->name = $name;
        $this->arguments = $arguments;
    }

    /**
     * Returns the expression's arguments.
     *
     * @return mixed
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Returns the expression's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
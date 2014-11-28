<?php

namespace Gaufrette\TestSuite\Exception;

class FailureException extends \Exception
{
    /**
     * @param string $part
     * @param mixed $expected
     * @param mixed $real
     *
     * @return void
     */
    public function __construct($part, $expected, $real)
    {
        if (true === is_string($expected) && 100 < strlen($expected)) {
            $expected = sprintf('string<%d>', strlen($expected));
        }

        if (true === is_string($real) && 100 < strlen($real)) {
            $real = sprintf('string<%d>', strlen($real));
        }

        if (true === is_array($expected)) {
            $expected = sprintf('[%s]', implode(', ', $expected));
        }

        if (true === is_array($real)) {
            $real = sprintf('[%s]', implode(', ', $real));
        }

        parent::__construct(sprintf('%s not equals, %s ecpected, %s given', ucfirst($part), $expected, $real));
    }
}

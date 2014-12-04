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
        parent::__construct(sprintf(
            '%s not equals, %s ecpected, %s given',
            ucfirst($part),
            $this->valueToString($expected),
            $this->valueToString($real)
        ));
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    private function valueToString($value)
    {
        if (true === is_string($value)) {
            if (100 < strlen($value)) {

                return sprintf('string<%d>', strlen($value));
            }

            return $value;
        }

        if (true === is_scalar($value)) {

            return (string) $value;
        }

        if (true === is_array($value)) {
            $exception = $this;
            $value     = array_map(function ($e) use ($exception) { return $exception->valueToString($e); }, $value);

            return sprintf('[ %s ]', implode(', ', $value));
        }

        if (true === ($value instanceof \DateTime)) {

            return $value->format('"r"');
        }

        if (true === is_object($value)) {

            return get_class($value);
        }
    }
}

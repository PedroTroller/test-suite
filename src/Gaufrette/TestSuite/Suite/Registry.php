<?php

namespace Gaufrette\TestSuite\Suite;

class Registry
{
    /**
     * @var Test[] $tests
     */
    private $tests;

    public function __construct()
    {
        $this->tests = [];
    }

    /**
     * @return Test[]
     */
    public function all()
    {
        return $this->tests;
    }
}

<?php

namespace Gaufrette\TestSuite\Suite;

use Gaufrette\TestSuite\Suite\Test\Content;
use Gaufrette\TestSuite\Suite\Test\Metadata;
use Gaufrette\TestSuite\Suite\Test\MimeType;
use Gaufrette\TestSuite\Suite\Test\Size;

class Registry
{
    /**
     * @var Test[] $tests
     */
    private $tests;

    public function __construct()
    {
        $this->tests = [];

        $this->tests[] = new Content;
        $this->tests[] = new MimeType;
        $this->tests[] = new Size;
        $this->tests[] = new Metadata;
    }

    /**
     * @return Test[]
     */
    public function all()
    {
        return $this->tests;
    }
}

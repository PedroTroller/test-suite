<?php

namespace Gaufrette\TestSuite\Suite;

use Gaufrette\TestSuite\Suite\Test\Configuration;
use Gaufrette\TestSuite\Suite\Test\Content;
use Gaufrette\TestSuite\Suite\Test\KeyList;
use Gaufrette\TestSuite\Suite\Test\LastAccess;
use Gaufrette\TestSuite\Suite\Test\LastModification;
use Gaufrette\TestSuite\Suite\Test\Metadata;
use Gaufrette\TestSuite\Suite\Test\MimeType;
use Gaufrette\TestSuite\Suite\Test\Size;

class Registry
{
    /**
     * @type Test[]
     */
    private $tests;

    public function __construct()
    {
        $this->tests = array();

        $this->tests[] = new Configuration();
        $this->tests[] = new Content();
        $this->tests[] = new KeyList();
        $this->tests[] = new LastAccess();
        $this->tests[] = new LastModification();
        $this->tests[] = new Metadata();
        $this->tests[] = new MimeType();
        $this->tests[] = new Size();
    }

    /**
     * @return Test[]
     */
    public function all()
    {
        return $this->tests;
    }
}

<?php

namespace Gaufrette\TestSuite\Suite;

use Gaufrette\Core\Adapter;

interface Test
{
    /**
     * @param Adapter $adapter
     *
     * @return boolean
     */
    public function supports(Adapter $adapter);

    /**
     * Test the adapter
     *
     * @param Gaufrette\Core\Adapter $adapter
     *
     * @return void
     *
     * @throw Gaufrette\TestSuite\Exception\FailureException
     */
    public function test(Adapter $adapter);

    /**
     * Get the sentence to display
     *
     * @return string
     */
    public function getSentence();
}

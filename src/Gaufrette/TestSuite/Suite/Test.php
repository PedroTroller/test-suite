<?php

namespace Gaufrette\TestSuite\Suite;

use Gaufrette\Core\Adapter;

interface Test
{
    /**
     * @param Adapter $adapter
     *
     * @return bool
     */
    public function supports(Adapter $adapter);

    /**
     * Test the adapter.
     *
     * @param Adapter $adapter
     *
     *
     * @throw Gaufrette\TestSuite\Exception\FailureException
     */
    public function run(Adapter $adapter);

    /**
     * Get the sentence to display.
     *
     * @return string
     */
    public function getSentence();
}

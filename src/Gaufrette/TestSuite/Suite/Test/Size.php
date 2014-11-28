<?php

namespace Gaufrette\TestSuite\Suite\Test;

use Gaufrette\Core\Adapter;
use Gaufrette\Core\Adapter\KnowsSize;
use Gaufrette\TestSuite\Exception\FailureException;
use Gaufrette\TestSuite\Suite\Test\AbstractTest;

class Size extends AbstractTest
{
    /**
     * {@inheritdoc}
     */
    public function supports(Adapter $adapter)
    {
        return $adapter instanceof KnowsSize;
    }

    /**
     * {@inheritdoc}
     */
    public function run(Adapter $adapter)
    {
        foreach ($this->getFiles() as $name) {
            $file  = $this->createFile($name);
            $clone = clone $file;
            $fs    = $this->createFilesystem($adapter);

            $fs->save($clone);

            if ($file->getSize() !== $clone->getSize()) {

                throw new FailureException('Size', $file->getSize(), $clone->getSize());
            }

            $clone = $fs->get($name);

            if ($file->getSize() !== $clone->getSize()) {

                throw new FailureException('Size', $file->getSize(), $clone->getSize());
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSentence()
    {
        return 'File size';
    }
}

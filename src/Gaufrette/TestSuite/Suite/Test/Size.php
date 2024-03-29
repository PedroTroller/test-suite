<?php

namespace Gaufrette\TestSuite\Suite\Test;

use Gaufrette\Core\Adapter;
use Gaufrette\TestSuite\Exception\FailureException;

class Size extends AbstractTest
{
    /**
     * {@inheritdoc}
     */
    public function supports(Adapter $adapter)
    {
        return $this->adapterHasBehavior($adapter, 'Gaufrette\Core\Adapter\KnowsSize');
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

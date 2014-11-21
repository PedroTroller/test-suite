<?php

namespace Gaufrette\TestSuite\Suite\Test;

use Gaufrette\Core\Adapter;
use Gaufrette\Core\Adapter\KnowsMimeType;
use Gaufrette\TestSuite\Exception\FailureException;
use Gaufrette\TestSuite\Suite\Test\AbstractTest;

class MimeType extends AbstractTest
{
    /**
     * {@inheritdoc}
     */
    public function supports(Adapter $adapter)
    {
        return $adapter instanceof KnowsMimeType;
    }

    /**
     * {@inheritdoc}
     */
    public function test(Adapter $adapter)
    {
        foreach ($this->getFiles() as $name) {
            $file  = $this->createFile($name);
            $clone = clone $file;
            $fs    = $this->createFilesystem($adapter);

            $fs->save($clone);

            if ($file->getMimeType() !== $clone->getMimeType()) {

                throw new FailureException(sprintf('Mime type not equals, %s expected, %s given', $file->getMimeType(), $clone->getMimeType()));
            }

            $clone = $fs->get($name);

            if ($file->getMimeType() !== $clone->getMimeType()) {

                throw new FailureException(sprintf('Mime type not equals, %s expected, %s given', $file->getMimeType(), $clone->getMimeType()));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSentence()
    {
        return 'File mime type';
    }
}

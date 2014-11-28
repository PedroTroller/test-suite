<?php

namespace Gaufrette\TestSuite\Suite\Test;

use Gaufrette\Core\Adapter;
use Gaufrette\Core\Adapter\KnowsContent;
use Gaufrette\TestSuite\Exception\FailureException;
use Gaufrette\TestSuite\Suite\Test\AbstractTest;

class Content extends AbstractTest
{
    /**
     * {@inheritdoc}
     */
    public function supports(Adapter $adapter)
    {
        return $adapter instanceof KnowsContent;
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

            if ($clone->getContent() !== $file->getContent()) {

                throw new FailureException('Content', $file->getContent(), $clone->getContent());
            }

            $clone = $fs->get($name);

            if ($clone->getContent() !== $file->getContent()) {

                throw new FailureException('Content', $file->getContent(), $clone->getContent());
            }
        }
    }

    public function getSentence()
    {
        return 'File content';
    }
}

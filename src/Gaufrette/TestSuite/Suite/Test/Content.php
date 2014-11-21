<?php

namespace Gaufrette\TestSuite\Suite\Test;

use Gaufrette\Core\Adapter;
use Gaufrette\TestSuite\Exception\FailureException;
use Gaufrette\TestSuite\Suite\Test\AbstractTest;

class Content extends AbstractTest
{
    public function supports(Adapter $adapter)
    {
        return true;
    }

    public function test(Adapter $adapter)
    {
        foreach ($this->getFiles() as $name) {
            $file  = $this->createFile($name);
            $clone = clone $file;
            $fs    = $this->createFilesystem($adapter);

            $fs->save($clone);

            if ($clone->getContent() !== $file->getContent()) {

                throw new FailureException('Content not equals');
            }

            $clone = $fs->get($name);

            if ($clone->getContent() !== $file->getContent()) {

                throw new FailureException('Content not equals');
            }
        }
    }

    public function getSentence()
    {
        return 'File content';
    }
}

<?php

namespace Gaufrette\TestSuite\Suite\Test;

use Gaufrette\Core\Adapter;
use Gaufrette\TestSuite\Exception\FailureException;

class Content extends AbstractTest
{
    /**
     * {@inheritdoc}
     */
    public function supports(Adapter $adapter)
    {
        return $this->adapterHasBehavior($adapter, 'Gaufrette\Core\Adapter\KnowsContent');
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

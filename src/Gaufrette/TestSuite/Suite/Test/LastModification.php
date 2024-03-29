<?php

namespace Gaufrette\TestSuite\Suite\Test;

use Gaufrette\Core\Adapter;
use Gaufrette\TestSuite\Exception\FailureException;

class LastModification extends AbstractTest
{
    /**
     * {@inheritdoc}
     */
    public function supports(Adapter $adapter)
    {
        return $this->adapterHasBehavior($adapter, 'Gaufrette\Core\Adapter\KnowsLastModification');
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

            if ($clone->getLastModification() != $file->getLastModification()) {
                throw new FailureException('Last Modification', $file->getLastModification(), $clone->getLastModification());
            }

            $clone = $fs->get($name);

            if ($clone->getLastModification() != $file->getLastModification()) {
                throw new FailureException('Last Modification', $file->getLastModification(), $clone->getLastModification());
            }
        }
    }

    public function getSentence()
    {
        return 'File last modification';
    }
}

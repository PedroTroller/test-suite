<?php

namespace Gaufrette\TestSuite\Suite\Test;

use Gaufrette\Core\Adapter;
use Gaufrette\TestSuite\Exception\FailureException;

class LastAccess extends AbstractTest
{
    /**
     * {@inheritdoc}
     */
    public function supports(Adapter $adapter)
    {
        return $this->adapterHasBehavior($adapter, 'Gaufrette\Core\Adapter\KnowsLastAccess');
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

            if ($clone->getLastAccess() != $file->getLastAccess()) {
                throw new FailureException('Last Access', $file->getLastAccess(), $clone->getLastAccess());
            }

            $clone = $fs->get($name);

            if ($clone->getLastAccess() != $file->getLastAccess()) {
                throw new FailureException('Last Access', $file->getLastAccess(), $clone->getLastAccess());
            }
        }
    }

    public function getSentence()
    {
        return 'File last access';
    }
}

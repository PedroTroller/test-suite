<?php

namespace Gaufrette\TestSuite\Suite\Test;

use Gaufrette\Core\Adapter;
use Gaufrette\Core\Adapter\CanListKeys;
use Gaufrette\TestSuite\Exception\FailureException;
use Gaufrette\TestSuite\Suite\Test\AbstractTest;

class KeyList extends AbstractTest
{
    public function supports(Adapter $adapter)
    {
        return $adapter instanceof CanListKeys;
    }

    public function run(Adapter $adapter)
    {
        $fs = $this->createFilesystem($adapter);

        foreach ($this->getFiles() as $name) {
            $file = $this->createFile($name);

            $fs->save($file);
        }

        if ($adapter->listKeys() !== $this->getFiles()) {

            throw new FailureException('Key list', $this->getFiles(), $adapter->getFiles());
        }
    }

    public function getSentence()
    {
        return 'File list';
    }
}

<?php

namespace Gaufrette\TestSuite\Suite\Test;

use Gaufrette\Core\Adapter;
use Gaufrette\TestSuite\Exception\FailureException;

class KeyList extends AbstractTest
{
    public function supports(Adapter $adapter)
    {
        return $this->adapterHasBehavior($adapter, 'Gaufrette\Core\Adapter\CanListKeys');
    }

    public function run(Adapter $adapter)
    {
        $fs = $this->createFilesystem($adapter);

        foreach ($this->getFiles() as $name) {
            $file = $this->createFile($name);

            $fs->save($file);
        }

        if ($adapter->listKeys() !== $this->getFiles()) {
            throw new FailureException('Key list', $this->getFiles(), $adapter->listKeys());
        }

        $filtered = array_values(array_filter($this->getFiles(), function ($e) { return 0 === strpos($e, 'music'); }));

        if (array_values($adapter->listKeys('music')) !== array_values($filtered)) {
            throw new FailureException('Key list', $filtered, $adapter->listKeys('music'));
        }

        if ($adapter->listKeys('no-value') !== array()) {
            throw new FailureException('Key list', array(), $adapter->listKeys('no-value'));
        }
    }

    public function getSentence()
    {
        return 'File list';
    }
}

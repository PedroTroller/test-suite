<?php

namespace Gaufrette\TestSuite\Suite\Test;

use Gaufrette\Core\Adapter;
use Gaufrette\TestSuite\Exception\FailureException;

class MimeType extends AbstractTest
{
    /**
     * {@inheritdoc}
     */
    public function supports(Adapter $adapter)
    {
        return $this->adapterHasBehavior($adapter, 'Gaufrette\Core\Adapter\KnowsMimeType');
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

            if ($file->getMimeType() !== $clone->getMimeType()) {
                throw new FailureException('MimeType', $file->getMimeType(), $clone->getMimeType());
            }

            $clone = $fs->get($name);

            if ($file->getMimeType() !== $clone->getMimeType()) {
                throw new FailureException('MimeType', $file->getMimeType(), $clone->getMimeType());
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

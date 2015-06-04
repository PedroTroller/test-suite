<?php

namespace Gaufrette\TestSuite\Suite\Test;

use Gaufrette\Core\Adapter;
use Gaufrette\TestSuite\Exception\FailureException;

class Metadata extends AbstractTest
{
    /**
     * {@inheritdoc}
     */
    public function supports(Adapter $adapter)
    {
        return $this->adapterHasBehavior($adapter, 'Gaufrette\Core\Adapter\KnowsMetadata');
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

            if ($file->getMetadata() !== $clone->getMetadata()) {
                throw new FailureException('Metadata', $file->getMetadata(), $clone->getMetadata());
            }

            $clone = $fs->get($name);

            if ($file->getMetadata() !== $clone->getMetadata()) {
                throw new FailureException('Metadata', $file->getMetadata(), $clone->getMetadata());
            }

            $metadata = array_merge($clone->getMetadata(), array('client' => 1, 'active' => true, 'legend' => 'the_legend' ));

            $file->setMetadata($metadata);

            $fs->save($file);
            $clone = $fs->get($name);

            if ($file->getMetadata() !== $clone->getMetadata()) {
                throw new FailureException('Metadata', $file->getMetadata(), $clone->getMetadata());
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSentence()
    {
        return 'File metadata';
    }
}

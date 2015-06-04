<?php

namespace Gaufrette\TestSuite\Suite\Test;

use Gaufrette\Core\Adapter;
use Gaufrette\Core\Behavior\Guesser;
use Gaufrette\Core\File\File;
use Gaufrette\Core\File\FileFactory\DefaultFileFactory;
use Gaufrette\Core\Filesystem\DefaultFilesystem;
use Gaufrette\Core\Operator\ContentOperator;
use Gaufrette\Core\Operator\FileOperator;
use Gaufrette\Core\Operator\LastAccessOperator;
use Gaufrette\Core\Operator\LastModificationOperator;
use Gaufrette\Core\Operator\MetadataOperator;
use Gaufrette\Core\Operator\MimeTypeOperator;
use Gaufrette\Core\Operator\SizeOperator;
use Gaufrette\TestSuite\Suite\Test;

abstract class AbstractTest implements Test
{
    /**
     * @param Adapter $adapter
     * @param string  $behavior
     *
     * @return boolean
     */
    protected function adapterHasBehavior(Adapter $adapter, $behavior)
    {
        $guesser = new Guesser();

        return $guesser->adapterHasBehavior($adapter, $behavior);
    }

    protected function getFiles()
    {
        $files     = array();
        $directory = new \DirectoryIterator($this->getPath());

        foreach ($directory as $file) {
            if (true === $file->isFile()) {
                $files[] = $file->getFilename();
            }
        }
        sort($files);

        return $files;
    }

    protected function getFilepath($file)
    {
        return sprintf('%s%s', $this->getPath(), $file);
    }

    protected function createFile($name)
    {
        $file = new File($name);

        $info    = new \finfo(FILEINFO_MIME_TYPE);
        $path    = $this->getFilepath($name);
        $content = file_get_contents($path);

        $file->setContent($content);
        $file->setMimeType($info->buffer($content));
        $file->setSize(filesize($path));
        $file->setLastAccess(new \DateTime('now', new \DateTimeZone("UTC")));
        $file->setLastModification(new \DateTime('-1 day', new \DateTimeZone("UTC")));

        return $file;
    }

    protected function createFilesystem(Adapter $adapter)
    {
        $fs = new DefaultFilesystem($adapter, new DefaultFileFactory());

        $fs->addOperator(new ContentOperator());
        $fs->addOperator(new SizeOperator());
        $fs->addOperator(new MetadataOperator());
        $fs->addOperator(new MimeTypeOperator());
        $fs->addOperator(new FileOperator());
        $fs->addOperator(new LastAccessOperator());
        $fs->addOperator(new LastModificationOperator());

        return $fs;
    }

    private function getPath()
    {
        return dirname(dirname(dirname(dirname(dirname(__DIR__))))).'/fixtures/';
    }
}

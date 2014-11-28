<?php

namespace Gaufrette\TestSuite\Suite\Test;

use Gaufrette\Core\Adapter;
use Gaufrette\Core\File\File;
use Gaufrette\Core\File\FileFactory\DefaultFileFactory;
use Gaufrette\Core\Filesystem\DefaultFilesystem;
use Gaufrette\Core\Operator\ContentOperator;
use Gaufrette\Core\Operator\FileOperator;
use Gaufrette\Core\Operator\MetadataOperator;
use Gaufrette\Core\Operator\MimeTypeOperator;
use Gaufrette\Core\Operator\SizeOperator;
use Gaufrette\TestSuite\Suite\Test;

abstract class AbstractTest implements Test
{
    public function getFiles()
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

    public function getFilepath($file)
    {
        return sprintf('%s%s', $this->getPath(), $file);
    }

    public function createFile($name)
    {
        $file = new File($name);

        $info    = new \finfo(FILEINFO_MIME_TYPE);
        $path    = $this->getFilepath($name);
        $content = file_get_contents($path);

        $file->setContent($content);
        $file->setMimeType($info->buffer($content));
        $file->setSize(filesize($path));

        return $file;
    }

    public function createFilesystem(Adapter $adapter)
    {
        $fs = new DefaultFilesystem($adapter, new DefaultFileFactory);

        $fs->addOperator(new ContentOperator());
        $fs->addOperator(new SizeOperator());
        $fs->addOperator(new MetadataOperator());
        $fs->addOperator(new MimeTypeOperator());
        $fs->addOperator(new FileOperator());

        return $fs;
    }

    private function getPath()
    {
        return dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/fixtures/';
    }
}

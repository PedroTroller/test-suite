<?php

namespace Gaufrette\TestSuite\Suite\Test;

use Gaufrette\Core\Adapter;
use Gaufrette\Core\File\File;
use Gaufrette\Core\File\FileFactory;
use Gaufrette\Core\Filesystem;
use Gaufrette\TestSuite\Suite\Test;

abstract class AbstractTest implements Test
{
    public function getFiles()
    {
        $files = array();
        $directory = new \DirectoryIterator($this->getPath());

        foreach ($directory as $file) {
            if ($file->isFile()) {
                $files[] = $file->getFilename();
            }
        }

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
        return new Filesystem($adapter, new FileFactory);
    }

    private function getPath()
    {
        return dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/fixtures/';
    }
}

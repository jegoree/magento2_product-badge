<?php

namespace Badge\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\File\Mime;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;



class FileInfo
{
    const ENTITY_MEDIA_PATH = 'badges';

    protected $filesystem;

    protected $mime;

    protected $mediaDirectory;

    public function __construct(
        Filesystem $filesystem,
        Mime $mime
    ) {
        $this->filesystem =$filesystem;
        $this->mime = $mime;
    }

    private function getMediaDirectory()
    {
        if($this->mediaDirectory === null) {
            $this->mediaDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        }
        return $this->mediaDirectory;
    }

    /**
     * Retrive MIME type of requested file
     *
     */
    public function getMimeType($fileName)
    {
        $filePath = self::ENTITY_MEDIA_PATH . '/' . ltrim($fileName, '/');
        $absoluteFilePath= $this->getMediaDirectory()->getAbsolutePath($filePath);

        $result = $this->mime->getMimeType($absoluteFilePath);
        return $result;
    }

    public function getStat($fileName)
    {
        $filePath = self::ENTITY_MEDIA_PATH . '/' . ltrim($fileName, '/');

        $result = $this->getMediaDirectory()->stat($filePath);
        return $result;
    }

    public function isExist($fileName, $baseTmpPath = false)
    {
        $filePath = self::ENTITY_MEDIA_PATH . '/' . ltrim($fileName, '/');
        if ($baseTmpPath) {
            $filePath = $baseTmpPath . '/' . ltrim($fileName, '/');
        }

        $result = $this->getMediaDirectory()->isExist($filePath);
        return $result;
    }

    public function deleteFile($fileName, $baseTmpPath = false)
    {
        $filePath = self::ENTITY_MEDIA_PATH . '/' . ltrim($fileName, '/');
        if ($baseTmpPath) {
            $filePath = $baseTmpPath . '/' . ltrim($fileName, '/');
        }

        return$this->getMediaDirectory()->delete($filePath);
    }
}
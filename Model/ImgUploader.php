<?php

namespace Badge\Model;

use Magento\Framework\App\ObjectManager;
use Badge\Model\FileInfo;
use \Magento\MediaStorage\Helper\File\Storage\Database;
use \Magento\Framework\Filesystem\Directory\WriteInterface;
use \Magento\MediaStorage\Model\File\UploaderFactory;
use \Magento\Store\Model\StoreManagerInterface;
use \Magento\Framework\Filesystem;
use \Magento\Framework\App\Filesystem\DirectoryList;
use \Psr\Log\LoggerInterface;
use \Magento\Framework\Exception\LocalizedException;
use \Magento\Framework\UrlInterface;

class ImgUploader
{
    /**
     * Core file storage database
     *
     * @var Database
     */
    protected $coreFileStorageDatabase;

    /**
     * Media directory object (writable)
     *
     * @var WriteInterface
     */
    protected $mediaDirectory;

    /**
     * Uplaoder factory
     *
     * @var UploaderFactory
     */
    protected $uploaderFactory;

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Base tmp path
     *
     * @var string
     */
    protected $baseTmpPath;

    /**
     * Base path
     *
     * @var string
     */
    protected $basePath;

    /**
     * Base extensions
     *
     * @var string
     */
    protected $allowedExtensions;

    /**
     * @var Filesystem
     */
    protected $fileInfo;


    public function __construct(
        Database $coreFileStorageDatabase,
        Filesystem $filesystem,
        UploaderFactory $uploaderFactory,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger,
        $baseTmpPath = 'tmp',
        $basePath = 'badges',
        $allowedExtensions = ['jpg', 'png', 'gif']
    ) {
        $this->coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->baseTmpPath = $baseTmpPath;
        $this->basePath = $basePath;
        $this->allowedExtensions = $allowedExtensions;
    }

    /**
     * Set base tmp path
     *
     * @param string $baseTmpPath
     *
     * @return void
     */
    public  function setBaseTmpPath($baseTmpPath)
    {
        $this->baseTmpPath = $baseTmpPath;
    }

    /**
     * Set base path
     *
     * @param string $basePath
     *
     * @return void
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * Set allowed extensions
     *
     * @param $allowedExtensions
     */
    public function setAllowedExtensions($allowedExtensions)
    {
        $this->allowedExtensions = $allowedExtensions;
    }

    /**
     * Retrieve base tmp path
     *
     * @return string
     */
    public function  getBaseTmpPath()
    {
        return $this->baseTmpPath;
    }

    /**
     * Retrieve base path
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Get allowed extensions
     *
     * @return mixed|string|string[]
     */
    public function getAllowedExtensions()
    {
        return $this->allowedExtensions;
    }

    /**
     * @param string $path
     * @param string $imageName
     *
     * @return String
     */
    public function getFilePath($path, $imageName)
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }

    /**
     * @param $imageName
     * @return mixed
     */
    public function moveFileFromTmp($imageName)
    {

        $baseTmpPath = $this->getBaseTmpPath();
        $basePath = $this->getBasePath();

        $baseImagePath = $this->getFilePath($basePath, $imageName);
        $baseTmpImagePath = $this->getFilePath($baseTmpPath, $imageName);

        try {
            if ($this->getFileInfo()->isExist($imageName, $this->baseTmpPath)){
                $this->coreFileStorageDatabase->copyFile(
                    $baseTmpImagePath,
                    $baseImagePath
                );
                $this->mediaDirectory->renameFile(
                    $baseTmpImagePath,
                    $baseImagePath
                );
            }
        } catch (\Exception $e) {
            throw new LocalizedException(
                __('Something went wrong while saving the file.')
            );
        }
        return '/media/' . $baseImagePath;
    }

    /**
     *
     *
     * @return Filesystem|mixed
     */
    private function getFileInfo()
    {
        if ($this->fileInfo === null) {
            $this->fileInfo = ObjectManager::getInstance()->get(FileInfo::class);
        }
        return $this->fileInfo;
    }

    /**
     * Checking file for save and save it on tmp dir
     *
     * @param string $fieldId
     *
     * @return string[]
     *
     * @throws LocalizedException
     */
    public function  saveFileToTmpDir($fileId)
    {
        $baseTmpPath = $this->getBaseTmpPath();

        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setAllowedExtensions($this->getAllowedExtensions());
        $uploader->setAllowRenameFiles(true);

        $result = $uploader->save($this->mediaDirectory->getAbsolutePath($baseTmpPath));
        unset($result['path']);

        if(!$result){
            throw new LocalizedException(
                __('File can not be saved to the destination folder')
            );
        }

        $result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
        $result['url'] = $this->storeManager
            ->getStore()
            ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA
            ) . $this->getFilePath($baseTmpPath, $result['file']);


        $result['name'] = $result ['file'];

        if (isset($result['file'])) {
            try {
                $relativePath = rtrim($baseTmpPath, '/') . '/' . ltrim($result['file'], '/');
                $this->coreFileStorageDatabase->saveFile($relativePath);
            } catch (\Exception $e) {
                $this->logger->critical($e);
                throw new LocalizedException(
                    __('Something went wrong while saving the file.')
                );
            }
        }
        return $result;
    }

    /**
     * @param $imageName
     * @param string $type
     */
    public function deleteImage($imageName, $type = 'dir')
    {
        $basePath = $this->getBasePath();
        if ($type == 'tmp') {
            $basePath = $this->getBaseTmpPath();
        }

        if ($this->getFileInfo()->isExist($imageName, $basePath)) {
            $this->getFileInfo()->deleteFile($imageName, $basePath);
        }
    }
}


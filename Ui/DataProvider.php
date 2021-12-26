<?php

namespace Badge\Ui;

use Magento\Ui\DataProvider\AbstractDataProvider;

use Magento\Framework\App\ObjectManager;
use Badge\Model\FileInfo;
use Magento\Framework\Filesystem;

class DataProvider extends AbstractDataProvider
{
    protected $collection;

    protected $fileInfo;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        $result = [];
        $items = $this->collection->getItems();

        foreach ($items as $item) {
            $item = $this->convertValues($item);
            $result[$item->getId()]['general'] = $item->getData();
        }
        return $result;
    }

    /**
     * Converts image to acceptable for rendering format
     *
     */
    protected function convertValues($item)
    {
        $fileName = $item->getImage();
        $image = [];
        if ($this->getFileInfo()->isExist($fileName)) {
            $stat = $this->getFileInfo()->getStat($fileName);
            $mime = $this->getFileInfo()->getMimeType($fileName);
            $image[0]['name'] = $fileName;
            $image[0]['url'] = $item->getImageUrl();
            $image[0]['size'] = isset($stat) ? $stat['size'] : 0;
            $image[0]['type'] = $mime;
        }
        $item->setImage($image);

        return $item;
    }

    /**
     * Get FileInfo instance
     */
    protected function getFileInfo() {
        if ($this->fileInfo === null) {
            $this->fileInfo = ObjectManager::getInstance()->get(FileInfo::class);
        }
        return $this->fileInfo;
    }
}
<?php

namespace Badge\Model;

use Magento\Framework\Model\AbstractModel;
use Badge\Model\ResourceModel\ItemsResource;
use Badge\Model\FileInfo;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\LocalizedException;

class Items extends AbstractModel
{
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    protected function _construct()
    {
        $this->_init(ItemsResource::class);
    }

    public function getImageUrl()
    {
        $url = '';
        $image = $this->getData('image');
        if($image) {
            if (is_string($image)) {
                $url = $this->_getStoreManager()->getStore()->getBaseUrl(
                    UrlInterface::URL_TYPE_MEDIA
                ).FileInfo::ENTITY_MEDIA_PATH .'/'. $image;
            } else {
                throw new LocalizedException(
                    __('Something went wrong while getting the image url')
                );
            }
        }
        return $url;
    }

    private  function _getStoreManager()
    {
        if ($this->_storeManager === null) {
            $this->_storeManager = ObjectManager::getInstance()->get(StoreManagerInterface::class);
        }
        return $this->_storeManager;
    }
}
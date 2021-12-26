<?php

namespace Badge\Controller\Adminhtml\Badge;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Badge\Model\ImgUploader;

class Upload extends Action
{

    /**
     * Image uploader (model)
     */
    protected $imageUploader;

    public function __construct(
        Context $context,
        ImgUploader $imageUploader
    )
    {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    public function execute()
    {
        $imageId = $this->_request->getParam('param_name', 'image');
        try {
            $result = $this->imageUploader->saveFileToTmpDir($imageId);

            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
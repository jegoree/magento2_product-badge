<?php

namespace Badge\Controller\Adminhtml\Badge;


use Badge\Model\ItemsFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Badge\Model\ImgUploader;

class Save extends Action
{
    /**
     * @var ItemsFactory
     */
    protected $itemsFactory;

    /**
     * @var ImgUploader
     */
    protected $imageUploader;

    /**
     * Save constructor.
     * @param Context $context
     * @param ItemsFactory $itemsFactory,
     * @param ImgUploader $imageUploader
     */
    public function __construct(
        Context $context,
        ItemsFactory $itemsFactory,
        ImgUploader $imageUploader
    ) {
        $this->itemsFactory = $itemsFactory;
        $this->imageUploader = $imageUploader;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue()['general'];

        $imageName = $data['image'][0]['name'];

        $imagePath = $this->imageUploader->moveFileFromTmp($imageName);

        $data['image'] = $imagePath;

        $this->itemsFactory->create()
            ->setData($data)
            ->save();
        return $this->resultRedirectFactory->create()->setPath('badgelisting/index/index');
    }
}

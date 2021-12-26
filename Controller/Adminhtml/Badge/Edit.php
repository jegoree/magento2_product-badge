<?php

namespace Badge\Controller\Adminhtml\Badge;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Badge\Model\Items;

/**
 * Class Index
 */
class Edit extends Action implements HttpGetActionInterface
{
    const MENU_ID = 'Badge::badges';

    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create(Items::class);

        $model->load($id);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu(static::MENU_ID);
        return $resultPage;
    }
}
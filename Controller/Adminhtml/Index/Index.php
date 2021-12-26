<?php

namespace Badge\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

class Index extends Action
{
    const MENU_ID = 'Badge::badges';

    public function execute()
    {
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE)
            ->setActiveMenu(static::MENU_ID);
    }
}
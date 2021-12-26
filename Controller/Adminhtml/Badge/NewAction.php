<?php

namespace Badge\Controller\Adminhtml\Badge;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action;

class NewAction extends Action
{
    const MENU_ID = 'Badge::badges';

    public function execute()
    {
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE)
            ->setActiveMenu(static::MENU_ID);
    }
}
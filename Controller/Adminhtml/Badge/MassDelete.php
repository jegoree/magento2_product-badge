<?php

namespace Badge\Controller\Adminhtml\Badge;

use Badge\Model\ItemsFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class MassDelete extends Action
{
    protected $itemsFactory;


    public function __construct(
        Context $context,
        ItemsFactory $itemsFactory
    ) {
        $this->itemsFactory = $itemsFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $ids = $this->getRequest()->getParam('selected', []);
        $post = $this->itemsFactory->create();
        foreach ($ids as $id){
            $result = $post->setId($id);
            $result->delete();
        }
        return $this->resultRedirectFactory->create()->setPath('badgelisting/index/index');
    }
}

<?php

namespace Badge\Controller\Adminhtml\Badge;

use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Badge\Model\ItemsFactory;
use Badge\Model\ResourceModel\ItemsResource;
use Psr\Log\LoggerInterface;
/**
 * Class Delete
 * Performs delete action for Badge
 */
class Delete extends Action
{
    /**
     * @var ItemsFactory
     */
    private $itemsFactory;

    /**
     * @var ItemsResource
     */
    private $itemsResource;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        Action\Context $context,
        ItemsFactory $itemsFactory,
        ItemsResource $itemsResource,
        LoggerInterface $logger
    ) {
        $this->itemsFactory = $itemsFactory;
        $this->itemsResource = $itemsResource;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * Executes delete action
     * And returns redirect path to Badge's index grid
     * @return ResponseInterface|Redirect|ResultInterface
     * @throws Exception
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('id');
        if ($id !== null) {
            $badge = $this->itemsFactory->create();
            try {
                $this->itemsResource->load($badge, $id, 'id');
                $this->itemsResource->delete($badge);
            } catch (\Exception $exception) {

            }
        }
        return $resultRedirect->setPath('badgelisting/index/index');
    }
}
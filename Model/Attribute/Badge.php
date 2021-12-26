<?php


namespace Badge\Model\Attribute;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Badge\Model\ResourceModel\Items\Collection;
use Badge\Model\ResourceModel\Items\CollectionFactory;

class Badge extends AbstractSource
{
    /**
     * @var CollectionFactory
     */
    protected $badgeCollectionFactory;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * StatusOptions constructor.
     *
     * @param CollectionFactory $badgeCollectionFactory
     */
    public function __construct(
        CollectionFactory $badgeCollectionFactory
    ) {
        $this->badgeCollectionFactory = $badgeCollectionFactory;
        $this->collection = $this->badgeCollectionFactory->create();
    }


    public function getAllOptions()
    {
        $this->collection = $this->badgeCollectionFactory->create();
        $badges = $this->collection->getItems();

        $options = [];
        $options[] = ['label' => 'No badge', 'value' => 0];

        foreach ($badges as $badge) {
            if ($badge->getEnabled()) {
                $options[] = [
                    'label' => $badge->getBadgeTitle(),
                    'value' => $badge->getImage()
                ];
            }
        }

        return $options;
    }
}
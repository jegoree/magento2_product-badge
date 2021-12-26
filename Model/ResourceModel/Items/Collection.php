<?php

namespace Badge\Model\ResourceModel\Items;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Badge\Model\Items;
use Badge\Model\ResourceModel\ItemsResource;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(Items::class, ItemsResource::class);
    }
}
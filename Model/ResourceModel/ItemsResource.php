<?php

namespace Badge\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ItemsResource extends AbstractDb
{
    /**
     * @returns data from related table
     */
    protected function _construct()
    {
        $this->_init('catalog_product_badge', 'id');
    }
}
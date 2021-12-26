<?php


namespace Badge\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Badge\Model\Attribute\Badge as Source;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;

class AddProductAttribute implements DataPatchInterface
{

    protected $eavSetupFactory;

    public function __construct(
        EavSetupFactory $eavSetupFactory
    ){
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function apply()
    {
        $eavSetup = $this->eavSetupFactory->create();
        $eavSetup->addAttribute(
            Product::ENTITY,
            'Badge',
            [
                'group'                     => 'General',
                'type'                      => 'varchar',
                'label'                     => 'Badge',
                'input'                     => 'select',
                'source'                    => Source::class,
                'frontend'                  => '',
                'backend'                   => '',
                'required'                  => false,
                'global'                    => ScopedAttributeInterface::SCOPE_GLOBAL,
                'is_used_in_grid'           => false,
                'is_visible_in_grid'        => false,
                'is_filtrable_in_grid'      => false,
                'visible'                   => true,
                'is_html_allowed_on_front'  => true,
                'visible_on_front'          => true,
            ]
        );
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }
}
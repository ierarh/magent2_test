<?php

namespace Jc\Upsale\Block\Widget;
 
class Upsale extends \Magento\Catalog\Block\Product\AbstractProduct implements \Magento\Widget\Block\BlockInterface
{
	/**
	 *
	 * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory 
	 */
	private $_produtsFactory;
	
	public function __construct( 
		\Magento\Catalog\Block\Product\Context $context,
		\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $produtsFactory,
        array $data = []
	)
	{
		$this->_produtsFactory = $collectionFactory;
		
		parent::__construct($context,$data);
	}

	protected function _beforeToHtml()
    {
    	$this->setTemplate('widget/product_upsale.phtml');
		
		parent::_beforeToHtml();
    }
	
	public function getProduct()
	{
		$key = 'product';
		
		if (!$this->hasData($key)) {
			$this->setData($key, $this->_produtsFactory->create()
            ->addAttributeToSelect('*')
            ->addFieldToFilter('up_sale_product', 1)
			->setPageSize(1)
			->setCurPage(1)
			->getFirstItem());
		}
		
		return $this->getData($key);
	}
	
}

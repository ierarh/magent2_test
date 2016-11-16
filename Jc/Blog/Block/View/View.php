<?php

namespace Jc\Blog\Block\View;

class View extends \Magento\Framework\View\Element\Template 
{
	/**
    * @var \Jc\Blog\Model\PostFactory
    */
	private $postFactory;

	/**
	 * @param Template\Context $context
	 * @param NewsFactory $newsFactory
	 * @param array $data
	 */
	public function __construct(
	   \Magento\Framework\View\Element\Template\Context $context,
	   \Jc\Blog\Model\PostFactory $postFactory,
	   array $data = []
	) {
		 $this->postFactory = $postFactory;
		 parent::__construct($context, $data);
	}
	
    /**
     * Set posts collection
     */
    protected  function _construct()
    {
        parent::_construct();
		$post_id = $this->getRequest()->getParam('post_id');
        $collection = $this->postFactory->create()->load($post_id);
        $this->setCollection($collection);
    }
}
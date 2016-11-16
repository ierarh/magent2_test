<?php

namespace Jc\Blog\Block;

class PostList extends \Magento\Framework\View\Element\Template 
{
	/**
	* @var \Jc\Blog\Model\PostFactory
	*/
	private $postsFactory;
	
	/**
	* @var \Magento\Store\Model\StoreManagerInterface
	*/
	private $storeManager;

	/**
	 * @param Template\Context $context
	 * @param NewsFactory $newsFactory
	 * @param array $data
	 */
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Jc\Blog\Model\ResourceModel\Post\CollectionFactory $postsFactory,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		array $data = []
	) {
		$this->postsFactory = $postsFactory;
		$this->storeManager = $storeManager;
        parent::__construct($context, $data);
	}
   
    /**
     * Set posts collection
     */
    protected  function _construct()
    {
        parent::_construct();
		$storeId = $this->storeManager->getStore()->getId();
		var_dump($storeId);
		//$block->setStoreId($storeId)->load($blockId);
        $collection = $this->postsFactory->create()->addStoreFilter($storeId)->setOrder('post_id', 'DESC');
        $this->setCollection($collection);
    }
	
   /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        /** @var \Magento\Theme\Block\Html\Pager */
        $pager = $this->getLayout()->createBlock(
           'Magento\Theme\Block\Html\Pager',
           'blog.post.list.pager'
        );
        $pager->setLimit(5)
            ->setShowAmounts(false)
            ->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
 
        return $this;
    }
	
	
   /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
	
}

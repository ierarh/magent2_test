<?php

namespace Jc\Blog\Model\ResourceModel\Post;
	 
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
			\Jc\Blog\Model\Post::class,
			\Jc\Blog\Model\ResourceModel\Post::class 
		);
    }
	
	/**
     * Add filter by store Id
     *
     * @return void
     */
	public function addStoreFilter($storeId)
	{
		return $this->addFilter('store_id', $storeId);	
	}

	/**
     * Join store relation table if there is store filter
     *
     * @return void
     */
    protected function _renderFiltersBefore()
    {
        if ($this->getFilter('store_id')) {
            $this->getSelect()->join(
                ['blog_store' => $this->getTable(\Jc\Blog\Model\ResourceModel\Post::TABLE_STORE_NAME)],
                'main_table.post_id = blog_store.post_id',
                []
            )->group(
                'main_table.post_id'
            );
        }
        parent::_renderFiltersBefore();
    }
}

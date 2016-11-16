<?php
 
namespace Jc\Blog\Model\ResourceModel;
	 
class Post extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	const TABLE_NAME = 'jc_blog_post';
	const TABLE_STORE_NAME = 'jc_blog_post_store';
	const ID_FIELD_NAME = 'post_id';
	
	/**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init( self::TABLE_NAME, self::ID_FIELD_NAME);
    }
		
    /**
     * Perform actions after object load
     *
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
	protected function _afterLoad( \Magento\Framework\Model\AbstractModel $object )
	{
		if ($object->getPostId()) {
			$connection = $this->getConnection();
			$select = $connection->select()
				->from(self::TABLE_STORE_NAME, 'store_id')
				->where('post_id = :post_id');

			$stores = $connection->fetchCol($select, ['post_id' => (int)$object->getPostId()]);
            $object->setData('store_ids', $stores);
        }
		
		parent::_afterLoad($object);
	}

	/**
     * Perform actions after object load
     *
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
		$table = $this->getTable( self::TABLE_STORE_NAME );
		$connection = $this->getConnection();
		$postId = $object->getPostId();
		$select = $connection->select()
            ->from($table, 'store_id')
            ->where('post_id = :post_id');

        $oldStores = $connection->fetchCol($select, ['post_id' => (int)$postId]);
		$newStores = $object->getStoreIds();
		
		$delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = [
                'post_id = ?' => (int)$postId,
                'store_id IN (?)' => $delete,
            ];
            $connection->delete($table, $where);
        }
		$insert = array_diff($newStores, $oldStores);
        if ($insert) {
            $data = [];
            foreach ($insert as $storeId) {
                $data[] = [
                    'post_id' => (int)$postId,
                    'store_id' => (int)$storeId,
                ];
            }
            $connection->insertMultiple($table, $data);
        }
		
        return parent::_afterSave($object);		
    }
}

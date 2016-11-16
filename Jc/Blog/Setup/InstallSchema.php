<?php

namespace Jc\Blog\Setup;

use Jc\Blog\Model\ResourceModel\Post;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
	public function install(
		\Magento\Framework\Setup\SchemaSetupInterface $setup,
		\Magento\Framework\Setup\ModuleContextInterface $context
	)
	{
		$installer = $setup;
		$installer->startSetup();
		
		$blog_table = $installer->getConnection()
			->newTable($installer->getTable( Post::TABLE_NAME ))
			->addColumn(
				Post::ID_FIELD_NAME,
				\Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
				null,
				['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
				'Post Id'
			)->addColumn(
				'title',
				\Magento\Framework\Db\Ddl\Table::TYPE_TEXT,
				255,
				['nullable' => false],
				'Post Title'
			)->addColumn(
				'content',
				\Magento\Framework\Db\Ddl\Table::TYPE_TEXT,
				'2M',
				[],
				'Post Content'
			)->addColumn(
				'creation_time',
				\Magento\Framework\Db\Ddl\Table::TYPE_TIMESTAMP,
				null,
				['nullable' => false, 'default' => \Magento\Framework\Db\Ddl\Table::TIMESTAMP_INIT],
				'Post Creation Time'
			)->setComment(
				'Jc Blog Post Table'
			);
		$store_table = $installer->getConnection()
			->newTable($installer->getTable( Post::TABLE_STORE_NAME ))
			->addColumn(
				'post_id',
				\Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
				null,
				['nullable' => false, 'unsigned' => true],
				'Post Id'
			)
			->addColumn(
				'store_id',
				\Magento\Framework\Db\Ddl\Table::TYPE_INTEGER,
				null,
				['nullable' => false, 'unsigned' => true],
				'Store Id'
			)->setComment(
				'Jc Blog Post To Store Table'
			);
		$installer->getConnection()->createTable($blog_table);
		$installer->getConnection()->createTable($store_table);
		$installer->endSetup();
	}
	
	
	
}

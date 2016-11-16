<?php

namespace Jc\Blog\Model;
	 
class Post extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init(\Jc\Blog\Model\ResourceModel\Post::class);
    }
}

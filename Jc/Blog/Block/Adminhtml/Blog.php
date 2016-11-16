<?php

namespace Jc\Blog\Block\Adminhtml;

class Blog extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'jc_blog';
        $this->_headerText = __('Blog Posts List');
        $this->_addButtonLabel = __('Add New Post');
        parent::_construct();
    }
	
}
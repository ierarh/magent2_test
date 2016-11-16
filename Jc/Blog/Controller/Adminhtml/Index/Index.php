<?php

namespace Jc\Blog\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;


class Index extends \Magento\Backend\App\Action
{
	
	public function execute()
    {
        $resultPage = $this->initResultPage();
        $resultPage->addBreadcrumb(__('Manage Posts'), __('Manage Posts'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Posts'));
        return $resultPage;
    }

    /**
     * Initialize action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function initResultPage()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Jc_Blog::blog')
            ->addBreadcrumb(__('Blog'), __('Blog'));
        return $resultPage;
    }
}
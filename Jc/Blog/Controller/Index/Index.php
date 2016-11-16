<?php

namespace Jc\Blog\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
	public function execute()
    {
        $page = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        return $page;
    }
}

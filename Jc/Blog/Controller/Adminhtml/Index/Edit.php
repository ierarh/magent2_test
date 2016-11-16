<?php

namespace Jc\Blog\Controller\Adminhtml\Index;

class Edit extends \Magento\Backend\App\Action
{
	
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
	
	protected $_coreRegistry;
	
	private $postFactory;

	/**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
		\Jc\Blog\Model\PostFactory $postFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
		$this->postFactory       = $postFactory;
		$this->_coreRegistry     = $coreRegistry;
		
        parent::__construct($context);
    }

    /**
     * Edit CMS block
     *
     * @return \Magento\Framework\Controller\ResultInterface
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        // 1. Get ID and create model
        $post_id = $this->getRequest()->getParam('post_id');
        $model = $this->postFactory->create();
        $this->_coreRegistry->register('post_id', $post_id);

        // 2. Initial checking
        if ($post_id) {
            $model->load($post_id);
            if (!$model->getPostId()) {
                $this->messageManager->addError(__('This post no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('current_post', $model);

        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Jc_Blog::blog')->addBreadcrumb(
            $post_id ? __('Edit Post') : __('New Post'),
            $post_id ? __('Edit Post') : __('New Post')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Posts'));
        $resultPage->getConfig()->getTitle()->prepend($model->getPostId() ? $model->getTitle() : __('New Post'));
        return $resultPage;
    }
	
	
}

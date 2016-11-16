<?php
namespace Jc\Blog\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Cms\Model\Block;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\TestFramework\Inspection\Exception;

class Save extends \Magento\Backend\App\Action
{
	/**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;
	
	/**
     * @var \Magento\Framework\Registry
     */
	protected $_coreRegistry;

	/**
     * @var \Jc\Blog\Model\PostFactory
     */
	private $postFactory;
	
	/**
     * @param Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Registry $coreRegistry,
		\Jc\Blog\Model\PostFactory $postFactory,
        DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->_coreRegistry = $coreRegistry;
		$this->postFactory   = $postFactory;
		
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {/** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $postData = $this->getRequest()->getPostValue();
		if ($postData) {
            $postId = $this->getRequest()->getParam('post_id');
			if (empty($postData['post_id'])) {
                $postData['post_id'] = null;
            }
			$model = $this->postFactory->create()->load($postId);
            if (!$model->getId() && $postId) {
                $this->messageManager->addError(__('This post no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
			$model->setData($postData);
			try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved the post.'));
                $this->dataPersistor->clear('current_post');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['post_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the post.'));
            }

            $this->dataPersistor->set('current_post', $postData);
            return $resultRedirect->setPath('*/*/edit', ['post_id' => $this->getRequest()->getParam('post_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}

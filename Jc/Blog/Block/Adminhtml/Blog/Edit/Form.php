<?php

namespace Jc\Blog\Block\Adminhtml\Blog\Edit;

use Magento\Tax\Api\TaxClassManagementInterface;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{


    /**
     * @var \Magento\Framework\Data\Form\FormKey
     */
    protected $formKey;
	
	/**
     * @var \Jc\Blog\Model\PostFactory
     */
	private $postFactory;
	
	/**
     * @var \Magento\Store\Model\System\Store
     */
	private $systemStore;

	/**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;


	/**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
		\Jc\Blog\Model\PostFactory $postFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
		\Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->formKey        = $context->getFormKey();
		$this->postFactory    = $postFactory;
		$this->_wysiwygConfig = $wysiwygConfig;
		$this->systemStore    = $systemStore;
		
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init class
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setId('jcPostForm');
        $this->setTitle(__('Post'));
        $this->setUseContainer(true);
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $postId = $this->_coreRegistry->registry('post_id');
        try {
            $post = $this->postFactory->create()->load($postId);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            /** Tax rule not found */
        }
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );
        $sessionFormValues = (array)$this->_coreRegistry->registry('blog_post_form_data');
        $postData = isset($post) ? $this->extractPostData($post) : [];
        $formValues = array_merge($postData, $sessionFormValues);

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Post Information')]);

        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'value' => isset($formValues['title']) ? $formValues['title'] : '',
                'label' => __('Title'),
                'class' => 'required-entry',
                'required' => true
            ]
        );
		$widgetFilters = ['is_email_compatible' => 1];
		$wysiwygConfig = $this->_wysiwygConfig->getConfig(['widget_filters' => $widgetFilters]);
        $fieldset->addField(
            'content',
            'editor',
            [
                'name' => 'content',
                'value' => isset($formValues['content']) ? $formValues['content'] : '',
                'label' => __('Content'),
				'state' => 'html',
				'style' => 'height: 200px;',
				'config' => $wysiwygConfig
            ]
        );
        $dateFormat = $this->_localeDate->getDateFormat(
            \IntlDateFormatter::MEDIUM
        );
        $timeFormat = $this->_localeDate->getTimeFormat(
            \IntlDateFormatter::MEDIUM
        );
        $fieldset->addField(
            'creation_time',
            'date',
            [
                'name' => 'creation_time',
                'value' => isset($formValues['creation_time']) ? $formValues['creation_time'] : '',
                'label' => __('Creation Time'),
                'date_format' => $dateFormat,
				'time_format' => $timeFormat,
            ]
        );
		$fieldset->addField(
			'store_ids',
			'multiselect',
			[
				'name'     => 'store_ids[]',
				'label'    => __('Store Views'),
				'title'    => __('Store Views'),
				'required' => true,
				'values'   => $this->systemStore->getStoreValuesForForm(false, true),
				'value'	   => isset($formValues['store_ids']) ? $formValues['store_ids'] : []
			]
		 );
       if (isset($post)) {
            $fieldset->addField(
                'post_id',
                'hidden',
                ['name' => 'post_id', 'value' => $post->getPostId(), 'no_span' => true]
            );
        }


        $form->setAction($this->getUrl('blog/index/save'));
        $form->setUseContainer($this->getUseContainer());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Extract tax rule data in a format which is
     *
     * @param \Magento\Tax\Api\Data\TaxRuleInterface $taxRule
     * @return array
     */
    protected function extractPostData($post)
    {
        $postData = [
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'creation_time' => $post->getCreationTime(),
            'store_ids' => $post->getStoreIds()
        ];
        return $postData;
    }
}

<?php
/**
 * Vendor
 *
 * @author Yuri Igumnov
 */
namespace Elogic\Vendor\Block\Adminhtml\Vendors\Edit;
class Form extends \Magento\Backend\Block\Widget\Form\Generic {
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }
    /**
     * Init form
     *
     * @return void
     */
    protected function _construct(){
        parent::_construct();
        $this->setId('vendor_form');
        $this->setTitle(__('Vendor'));
    }

    /**
     * Prepare form
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm(){
        $model = $this->_coreRegistry->registry('vendor');
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id'     => 'edit_form',
                    'action' => $this->getData('action'),
                    'method' => 'post',
                    'enctype'=> 'multipart/form-data'
                ]
            ]
        );

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General')]
        );

        if ($model->getId()) {
            $fieldset->addField(
                'id',
                'hidden',
                ['name' => 'id']
            );
        }

        if ($model->getId()) {
            $fieldset->addField(
                'created_at',
                'text',
                [
                    'name' => 'created_at',
                    'label' => __('Created at'),
                    'readonly' => true
                ]
            );

            $fieldset->addField(
                'updated_at',
                'text',
                [
                    'name' => 'updated_at',
                    'label' => __('Updated at'),
                    'readonly' => true
                ]
            );
        }

        $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label'	=> __('Name'),
                'required' => true
            ]
        );
        $fieldset->addField(
            'description',
            'textarea',
            [
                'name' => 'description',
                'label' => __('Description'),
                'required' => true,
                'style' => 'height: 15em; width: 100%;'
            ]
        );
        $fieldset->addField(
            'logo',
            'image',
            [
                'name' => 'logo',
                'label'	=> __('Logo'),
                'required' => false,
                'note' => 'Allowed image types: jpg, jpeg, png'
            ]
        );
        $fieldset->addField(
            'active',
            'select',
            [
                'name' => 'active',
                'label'	=> __('Active'),
                'required' => true,
                'values' => [
                    ['value'=>"1",'label'=>__('Yes')],
                    ['value'=>"0",'label'=>__('No')]
                ]
            ]
        );
        $data = $model->getData();
        $form->setValues($data);
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
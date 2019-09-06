<?php
/**
 * Vendor
 *
 * @author Yuri Igumnov
 */
namespace Elogic\Vendor\Block\Adminhtml\Vendors;
class Edit extends \Magento\Backend\Block\Widget\Form\Container {
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;
    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }
    /**
     * Vendor edit block
     *
     * @return void
     */
    protected function _construct(){
        $this->_objectId = 'id';
        $this->_blockGroup = 'Elogic_Vendor';
        $this->_controller = 'adminhtml_vendors';
        parent::_construct();
        if ($this->_isAllowedAction('Elogic_Vendor::vendors')) {
            $this->buttonList->remove('reset');
            $this->buttonList->update('save', 'label', __('Save Item'));
            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ]
                ],
                -100
            );
        } else {
            $this->buttonList->remove('save');
        }
        if ($this->_isAllowedAction('Elogic_Vendor::vendors')) {
            $this->buttonList->update('delete', 'label', __('Delete Vendor'));
        } else {
            $this->buttonList->remove('delete');
        }
    }
    /**
     * Get header with Vendor name
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText(){
        if ($this->_coreRegistry->registry('vendor')->getId()) {
            return __("Edit Vendor '%1'", $this->escapeHtml($this->_coreRegistry->registry('vendor')->getData('title')));
        } else {
            return __('New Vendor');
        }
    }
    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId){
        return $this->_authorization->isAllowed($resourceId);
    }
    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl(){
        return $this->getUrl('vendor/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
    }
    protected function _prepareLayout(){
        $this->_formScripts[] = "
			function toggleEditor() {
				if (tinyMCE.getInstanceById('general_content') == null) {
					tinyMCE.execCommand('mceAddControl', false, 'general_content');
				} else {
					tinyMCE.execCommand('mceRemoveControl', false, 'general_content');
				}
			};
		";
        return parent::_prepareLayout();
    }
}
<?php
namespace Elogic\Vendor\Controller\Adminhtml\Vendors;
use \Magento\Backend\App\Action;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;
class Delete extends Action {
    protected $_resultPageFactory;
    protected $_resultPage;
    protected $_model;
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Elogic\Vendor\Model\Vendor $model
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->_model = $model;
    }
    public function execute(){
        $id = $this->getRequest()->getParam('id');
        if($id>0){
            $model = $this->_model;
            $model->load($id);
            try {
                $model->delete();
            } catch (\Exception $e) {
                $this->messageManager->addError(__('Something went wrong.'));
            }
        }
        $this->_redirect('vendor/vendors');
    }
    protected function _isAllowed(){
        return $this->_authorization->isAllowed('Elogic_Vendor::vendor_delete');
    }
}
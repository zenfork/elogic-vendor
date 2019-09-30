<?php
namespace Elogic\Vendor\Controller\Adminhtml\Vendors;
use Elogic\Vendor\Api\VendorRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;
class Delete extends Action {
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
    /**
     * @var VendorRepositoryInterface
     */
    protected $_vendorRepository;

    /**
     * Delete constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param VendorRepositoryInterface $vendorRepository
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        VendorRepositoryInterface $vendorRepository
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->_vendorRepository = $vendorRepository;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute(){
        $id = $this->getRequest()->getParam('id');
        if($id>0){
            try {
                $vendor = $this->_vendorRepository->getById($id);
                try {
                    $this->_vendorRepository->delete($vendor);
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage(__('Something went wrong when deleting vendor.'));
                }
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('ID with such vendor not found.'));
            }
        }
        $this->_redirect('vendor/vendors');
    }

    /**
     * @return bool
     */
    protected function _isAllowed(){
        return $this->_authorization->isAllowed('Elogic_Vendor::vendor_delete');
    }
}
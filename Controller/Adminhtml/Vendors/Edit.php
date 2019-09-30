<?php
namespace Elogic\Vendor\Controller\Adminhtml\Vendors;
use Elogic\Vendor\Api\VendorRepositoryInterface;
use Elogic\Vendor\Api\Data\VendorInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Edit extends \Magento\Backend\App\Action{
    /**
     * @var Registry|null
     */
    protected $_coreRegistry = null;
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
    /**
     * @var VendorRepositoryInterface
     */
    protected $_vendorRepository;
    /**
     * @var VendorInterface
     */
    protected $_vendor;

    /**
     * Edit constructor.
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $registry
     * @param VendorRepositoryInterface $vendorRepository
     * @param VendorInterface $vendor
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        Registry $registry,
        VendorRepositoryInterface $vendorRepository,
        VendorInterface $vendor
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->_vendorRepository = $vendorRepository;
        $this->_vendor = $vendor;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    protected function _isAllowed(){
        return $this->_authorization->isAllowed('Elogic_Vendor::vendor_save');
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    protected function _initAction(){
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Elogic_Vendor::vendors')
            ->addBreadcrumb(__('Vendors'), __('Vendors'))
            ->addBreadcrumb(__('Edit'), __('Edit'));
        return $resultPage;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute(){
        $id = $this->getRequest()->getParam('id');

        // If you have got an id, it's edition
        if ($id > 0) {
            try {
                $vendor = $this->_vendorRepository->getById($id);

                $data = $this->_getSession()->getFormData(true);
                if (!empty($data)) {
                    $vendor->setData($data);
                }
                $this->_coreRegistry->register('vendor', $vendor);

                if (!$vendor->getId()) {
                    $this->messageManager->addErrorMessage(__('This vendor not exists.'));
                    /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                    $resultRedirect = $this->resultRedirectFactory->create();

                    return $resultRedirect->setPath('*/*/');
                }
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('ID with such vendor not found.'));
            }
        }

        $resultPage = $this->_initAction();

        $resultPage->addBreadcrumb(
            $id ? __('Edit Vendor') : __('New Vendor'),
            $id ? __('Edit Vendor') : __('New Vendor')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Vendors'));
        $resultPage->getConfig()->getTitle()
            ->prepend($vendor->getId() ? $vendor->getName() : __('New Vendor'));

        return $resultPage;
    }
}
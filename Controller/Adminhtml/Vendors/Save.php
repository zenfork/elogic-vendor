<?php
namespace Elogic\Vendor\Controller\Adminhtml\Vendors;
use Elogic\Vendor\Api\Data\VendorInterface;
use Elogic\Vendor\Api\VendorRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Request\Http;
class Save extends Action {
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
    /**
     * @var VendorInterface
     */
    protected $_model;
    /**
     * @var VendorRepositoryInterface
     */
    protected $_vendorRepository;
    /**
     * @var Http
     */
    protected $_request;

    /**
     * Save constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param VendorInterface $model
     * @param VendorRepositoryInterface $vendorRepository
     * @param Http $request
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        VendorInterface $model,
        VendorRepositoryInterface $vendorRepository,
        Http $request
    ){
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->_model = $model;
        $this->_vendorRepository = $vendorRepository;
        $this->_request = $request;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(){
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $id = $this->getRequest()->getParam('id');
            if ($id > 0) {
                try {
                    $vendor = $this->_vendorRepository->getById($id);
                    if ($id != $vendor->getId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
                    }
                    if ($this->_request->getFiles('logo')['size'] > 0) {
                        try {
                            $uploader = $this->_objectManager->create(
                                'Magento\MediaStorage\Model\File\Uploader',
                                ['fileId' => 'logo']
                            );
                            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                            /** @var \Magento\Framework\Image\Adapter\AdapterInterface $imageAdapter */
                            $imageAdapter = $this->_objectManager->get('Magento\Framework\Image\AdapterFactory')->create();
                            $uploader->setAllowRenameFiles(true);
                            $uploader->setFilesDispersion(true);
                            /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
                            $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                                ->getDirectoryRead(DirectoryList::MEDIA);
                            $result = $uploader->save($mediaDirectory->getAbsolutePath('vendor'));
                            if ($result['file']) {
                                $this->messageManager->addSuccessMessage(__('Logo has been successfully uploaded'));
                            }
                            if ($result['error'] == 0) {
                                $data['logo'] = 'vendor' . $result['file'];
                            }
                        } catch (\Exception $e) {
                            $this->messageManager->addExceptionMessage($e, __('Uploading of logo was failed.'));
                        }
                    }

                    if (isset($data['logo']['delete']) && $data['logo']['delete'] == '1')
                        $data['logo'] = '';
                    if (isset($data['logo']['value']) && strlen($data['logo']['value']) > 1)
                        $data['logo'] = $data['logo']['value'];

                    $vendor->setData($data);

                    try {
                        $this->_vendorRepository->save($vendor);
                        $this->messageManager->addSuccessMessage(__('Saved.'));
                        if ($this->getRequest()->getParam('back')) {
                            return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                        }
                        $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                        return $resultRedirect->setPath('*/*/');
                    } catch (\Exception $e) {
                        $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving.'));
                    }
                    $this->_getSession()->setFormData($data);
                } catch (NoSuchEntityException $e) {
                    $this->messageManager->addErrorMessage(__('ID with such vendor not found.'));
                }
            }
        }
        return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
    }

    /**
     * @return bool
     */
    protected function _isAllowed(){
        return $this->_authorization->isAllowed('Elogic_Vendor::vendor_save');
    }
}
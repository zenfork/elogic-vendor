<?php
namespace Elogic\Vendor\Controller\Adminhtml\Vendors;
use Elogic\Vendor\Api\Data\VendorInterface;
use Elogic\Vendor\Api\VendorRepositoryInterface;
use Elogic\Vendor\Model\VendorFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistor;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Request\Http;
class Save extends \Magento\Backend\App\Action {
    /**
     * @var VendorFactory
     */
    private $vendorFactory;
    /**
     * @var VendorInterface
     */
    protected $model;
    /**
     * @var VendorRepositoryInterface
     */
    protected $vendorRepository;
    /**
     * @var Http
     */
    protected $request;
    /**
     * @var DataPersistor
     */
    private $dataPersistor;
    private $imageUploader;

    /**
     * Save constructor.
     * @param Context $context
     * @param DataPersistor $dataPersistor
     * @param VendorFactory $vendorFactory
     * @param VendorInterface $model
     * @param VendorRepositoryInterface $vendorRepository
     * @param Http $request
     */
    public function __construct(
        Context $context,
        DataPersistor $dataPersistor,
        VendorFactory $vendorFactory,
        VendorInterface $model,
        VendorRepositoryInterface $vendorRepository,
        Http $request
    ){
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
        $this->vendorFactory = $vendorFactory;
        $this->model = $model;
        $this->vendorRepository = $vendorRepository;
        $this->request = $request;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(){
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $vendorId = null;
        $vendorPostData = $this->getRequest()->getPostValue();

        $isNewVendor = empty($vendorPostData['id']);

        if ($vendorPostData) {
            if ($isNewVendor) {
                $vendorPostData['id'] = null;
            }
            $vendorId = $this->getRequest()->getParam('id');
            if ($vendorId > 0) {
                try {
                    $vendor = $this->vendorRepository->getById($vendorId);
                    if ($vendorId != $vendor->getId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
                    }
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This vendor no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $vendorPostData = $this->_filterVendorData($vendorPostData);

            /** @var \Elogic\Vendor\Model\Vendor $vendor */
            $vendor = $this->vendorFactory->create();

            $vendor->setData($vendorPostData);

            try {
                $this->vendorRepository->save($vendor);
                $vendorId = $vendor->getId();
                $this->messageManager->addSuccessMessage(__('Saved.'));
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving.'));
            }

            $this->dataPersistor->set('elogic_vendor_vendor', $vendorPostData);
        }

        $redirectParams = $this->getRedirectParams($isNewVendor, $vendorId);

        return $resultRedirect->setPath(
            $redirectParams['path'],
            $redirectParams['params']
        );
    }

    /**
     * Set image to null if it is deleted
     *
     * @param array $rawData
     * @return array
     */
    public function _filterVendorData($rawData)
    {
        $data = $rawData;

        // is set "name" and "tmp_name"
        if (isset($data['logo'][0]['name']) && isset($data['logo'][0]['tmp_name'])) {
            $data['logo'] = $data['logo'][0]['name'];
            $imageUploader = \Magento\Framework\App\ObjectManager::getInstance()->get(
                'VendorImageUploader'
            );
            $imageUploader->moveFileFromTmp($data['logo']);
        // only image without "tmp_name"
        } elseif (isset($data['logo'][0]['image']) && !isset($data['logo'][0]['tmp_name'])) {
            $data['logo'] = $data['logo'][0]['image'];
        } else {
            $data['logo'] = null;
        }

        return $data;
    }

    /**
     * @return bool
     */
    protected function _isAllowed(){
        return $this->_authorization->isAllowed('Elogic_Vendor::vendor_save');
    }

    /**
     * Get category redirect path
     *
     * @param bool $isNewVendor
     * @param bool $hasError
     * @param int $vendorId
     * @return array
     */
    protected function getRedirectParams($isNewVendor, $vendorId)
    {
        $params = [];
        if (!$this->getRequest()->getParam('back')) {
            $path = '*/*/';
        }
        elseif ($isNewVendor) {
            $path = 'vendor/*/add';
        } else {
            $path = 'vendor/*/edit';
            $params['id'] = $vendorId;
        }
        return ['path' => $path, 'params' => $params];
    }
}
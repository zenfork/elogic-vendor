<?php
namespace Elogic\Vendor\Controller\Adminhtml\Vendors;
use \Magento\Backend\App\Action;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
class Save extends Action {
    protected $_resultPageFactory;
    protected $_resultPage;
    protected $_model;
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Elogic\Vendor\Model\Vendor $model
    ){
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->_model = $model;
    }
    public function execute(){
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->_model;
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
                if ($id != $model->getId()) {
                    throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
                }
            }
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
                if($result['error']==0)
                {
                    $data['logo'] = 'vendor' . $result['file'];
                }
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Uploading of logo was failed.'));
            }
            if (isset($data['logo']['delete']) && $data['logo']['delete'] == '1')
                $data['logo'] = '';
            if (isset($data['logo']['value']) && strlen($data['logo']['value']) > 1)
                $data['logo'] = $data['logo']['value'];

            $model->setData($data);
            try {
                $model->save();
                $this->messageManager->addSuccess(__('Saved.'));
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving.'));
            }

            $this->_getSession()->setFormData($data);
        }
        return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
    }
    protected function _isAllowed(){
        return $this->_authorization->isAllowed('Elogic_Vendor::vendor_save');
    }
}
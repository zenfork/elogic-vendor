<?php
/**
 * Vendor
 *
 * @author Yuri Igumnov
 */
namespace Elogic\Vendor\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Store\Model\StoreManagerInterface;
use \Magento\Framework\ObjectManagerInterface;
use \Magento\Framework\App\Helper\Context;
use \Magento\Store\Model\ScopeInterface;
class Data extends AbstractHelper {
    const VENDOR_ATTR = 'elogic_vendor';
    private $objectManager;
    protected $_model;
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Elogic\Vendor\Model\Vendor $model
    ){
        $this->objectManager = $objectManager;
        $this->_model = $model;
        parent::__construct($context);
    }
    public function getVendors(){
        $model = $this->_model;
        $collection = $model->getCollection();
        $collection->addFieldToFilter('active',  $model->getActiveStatus());
        return $collection;
    }
    public function getVendorsByIds($vendorIds = 0){
        $model = $this->_model;
        $collection = $model->getCollection();
        $collection->addFieldToFilter('active', $model->getActiveStatus());
        $collection->addFieldToFilter('id', ['in' => $vendorIds]);
        return $collection;
    }
}
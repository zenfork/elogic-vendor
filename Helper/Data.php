<?php
/**
 * Vendor
 *
 * @author Yuri Igumnov
 */
namespace Elogic\Vendor\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Framework\App\Helper\Context;
use \Elogic\Vendor\Api\Data\VendorInterface;
use \Elogic\Vendor\Model\ResourceModel\Vendor\CollectionFactory as VendorCollectionFactory;
class Data extends AbstractHelper {
    const VENDOR_ATTR = 'elogic_vendor';
    /**
     * @var VendorInterface
     */
    protected $_vendor;
    /**
     * @var VendorCollectionFactory
     */
    protected $_vendorCollectionFactory;

    /**
     * Data constructor.
     * @param Context $context
     * @param VendorInterface $vendor
     * @param VendorCollectionFactory $vendorCollectionFactory
     */
    public function __construct(
        Context $context,
        VendorInterface $vendor,
        VendorCollectionFactory $vendorCollectionFactory
    ){
        $this->_vendor = $vendor;
        $this->_vendorCollectionFactory = $vendorCollectionFactory;
        parent::__construct($context);
    }

    /**
     * @return \Elogic\Vendor\Model\ResourceModel\Vendor\Collection
     */
    public function getVendors(){
        $vendor = $this->_vendor;
        $collection = $this->_vendorCollectionFactory->create();
        $collection->addFieldToFilter('active',  $vendor->getActiveStatus());
        return $collection;
    }

    /**
     * @param int $vendorIds
     * @return \Elogic\Vendor\Model\ResourceModel\Vendor\Collection
     */
    public function getVendorsByIds($vendorIds = 0){
        $vendor = $this->_vendor;
        $collection = $this->_vendorCollectionFactory->create();
        $collection->addFieldToFilter('active', $vendor->getActiveStatus());
        $collection->addFieldToFilter('id', ['in' => $vendorIds]);
        return $collection;
    }
}
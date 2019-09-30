<?php
namespace Elogic\Vendor\Block\Catalog\Product\View\Vendors;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Registry;
use Elogic\Vendor\Helper\Data;
use Elogic\Vendor\Api\Data\VendorInterface;
use Elogic\Vendor\Model\ResourceModel\Vendor\CollectionFactory as VendorCollectionFactory;
class Listing extends \Magento\Framework\View\Element\Template {
    /**
     * @var Data
     */
    private $helper;
    /**
     * @var \Elogic\Vendor\Model\ResourceModel\Vendor\Collection
     */
    private $collection;
    /**
     * @var mixed
     */
    private $product;
    /**
     * @var VendorCollectionFactory
     */
    private $vendorCollectionFactory;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Listing constructor.
     * @param Context $context
     * @param Registry $registry
     * @param StoreManagerInterface $storeManager
     * @param Data $helper
     * @param VendorCollectionFactory $vendorCollectionFactory
     * @param VendorInterface $vendor
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        StoreManagerInterface $storeManager,
        Data $helper,
        VendorCollectionFactory $vendorCollectionFactory,
        VendorInterface $vendor,
        array $data
    ){
        $this->helper = $helper;
        $this->product = $registry->registry('product');
        $this->vendorCollectionFactory = $vendorCollectionFactory;
        $collection = $this->vendorCollectionFactory->create()
            ->addFieldToFilter('active', true)->setOrder('id', 'asc');
        $this->collection = $collection;
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }
    public function getHelper(){
        return $this->helper;
    }
    public function getProduct(){
        return $this->product;
    }
    public function getVendor($vendorId = 0) {
        return $this->getHelper()->getItem($vendorId, $this->getProduct()->getId());
    }
    public function getAllVendors() {
        $vendor_ids = explode(',', $this->product->getData(Data::VENDOR_ATTR));
        return $this->helper->getVendorsByIds($vendor_ids);
    }
    public function getFullImageUrl($path) {
        return $this->storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA ) . $path;
    }
}
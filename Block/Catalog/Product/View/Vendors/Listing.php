<?php
namespace Elogic\Vendor\Block\Catalog\Product\View\Vendors;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Registry;
use Elogic\Vendor\Helper\Data;
use Elogic\Vendor\Model\Vendor;
class Listing extends \Magento\Framework\View\Element\Template {
    private $helper;
    private $collection;
    private $product;

    /**
     * Listing constructor.
     * @param Context $context
     * @param Registry $registry
     * @param StoreManagerInterface $storeManager
     * @param Data $helper
     * @param Vendor $_vendor
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        StoreManagerInterface $storeManager,
        Data $helper,
        Vendor $_vendor,
        array $data
    ){
        $this->helper = $helper;
        $this->product = $registry->registry('product');
        $collection = $_vendor->getCollection()
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
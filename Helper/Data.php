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
    private $objectManager;
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ){
        $this->objectManager = $objectManager;
        parent::__construct($context);
    }
    public function getVendors(){
        $collection = $this->objectManager->get('Elogic\Vendor\Model\Vendor')->getCollection();
        return $collection;
    }
}
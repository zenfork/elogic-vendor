<?php
/**
 * Vendor
 *
 * @author Yuri Igumnov
 */
namespace Elogic\Vendor\Model\Entity\Attribute\Source;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
class Vendor extends AbstractSource
{
    private $helper;

    public function __construct(
        \Elogic\Vendor\Helper\Data $helper
    ){
        $this->helper = $helper;
    }

    public function getAllOptions()
    {
        $collection = $this->helper->getVendors();
        if ($collection->count() > 0) {
            foreach ($collection as $vendor) {
                $this->_options[] = [
                    'label' => __($vendor->getData('name')),
                    'value' => $vendor->getData('id'),
                ];
            }
        }
        return $this->_options;
    }
}
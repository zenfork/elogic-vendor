<?php
/**
 * Vendor
 *
 * @author Yuri Igumnov
 */
namespace Elogic\Vendor\Model\ResourceModel\Vendor;
use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
class Collection extends AbstractCollection {
    protected function _construct() {
        $this->_init(
            'Elogic\Vendor\Model\Vendor',
            'Elogic\Vendor\Model\ResourceModel\Vendor'
        );
    }
}
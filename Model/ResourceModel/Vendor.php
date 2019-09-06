<?php
/**
 * Vendor
 *
 * @author Yuri Igumnov
 */
namespace Elogic\Vendor\Model\ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
class Vendor extends AbstractDb {
    protected function _construct() {
        $this->_init('elogic_vendors', 'id');
    }
}

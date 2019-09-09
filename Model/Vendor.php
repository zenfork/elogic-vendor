<?php
/**
 * Vendor
 *
 * @author Yuri Igumnov
 */
namespace Elogic\Vendor\Model;
use Magento\Framework\Model\AbstractModel;
class Vendor extends AbstractModel {
    protected function _construct() {
        $this->_init('Elogic\Vendor\Model\ResourceModel\Vendor');
    }
    /*
     * ToDo: if needed use afterDelete() to clean eav attributes
     */
}
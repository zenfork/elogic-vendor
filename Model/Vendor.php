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
    /*public function afterDelete(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $helper = $objectManager->get('Elogic\Vendor\Helper\Data'); // !
        $items = $helper->getItemsByWarehouseId($this->getData('id'));
        if($items->count()>0){
            foreach ($items as $item) {
                $item->delete();
            }
        }
        parent::afterDelete();
    }*/
}
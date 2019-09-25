<?php
/**
 * Vendor
 *
 * @author Yuri Igumnov
 */
namespace Elogic\Vendor\Model;

use Elogic\Vendor\Api\Data\VendorInterface;
use Magento\Framework\Model\AbstractModel;

class Vendor extends AbstractModel implements VendorInterface {
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const LOGO = 'logo';
    const ACTIVE = 'active';

    protected function _construct()
    {
        $this->_init(ResourceModel\Vendor::class);
    }

    public function getName()
    {
        return $this->_getData(self::NAME);
    }

    public function setName($name)
    {
        $this->setData(self::NAME, $name);
    }

    public function getDescription()
    {
        return $this->_getData(self::DESCRIPTION);
    }

    public function setDescription($description)
    {
        $this->setData(self::DESCRIPTION, $description);
    }

    public function getLogo()
    {
        $this->_getData(self::LOGO);
    }

    public function setLogo($logo)
    {
        $this->setData(self::LOGO, $logo);
    }

    public function getActive()
    {
        $this->_getData(self::ACTIVE);
    }

    public function setActive($active)
    {
        return $this->setData(self::ACTIVE, $active);
    }
}
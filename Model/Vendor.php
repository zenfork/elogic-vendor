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

    private $ignoreFormFields = ['created_at', 'updated_at'];

    protected function _construct()
    {
        $this->_init(ResourceModel\Vendor::class);
    }

    /**
     * @return mixed|string
     */
    public function getName()
    {
        return $this->_getData(self::NAME);
    }

    /**
     * @param string $name
     * @return VendorInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @return mixed|string
     */
    public function getDescription()
    {
        return $this->_getData(self::DESCRIPTION);
    }

    /**
     * @param string $description
     * @return VendorInterface
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * @return mixed|string
     */
    public function getLogo()
    {
        return $this->_getData(self::LOGO);
    }

    /**
     * @param string $logo
     * @return VendorInterface
     */
    public function setLogo($logo)
    {
        return $this->setData(self::LOGO, $logo);
    }

    /**
     * @return mixed|string
     */
    public function getActive()
    {
        return $this->_getData(self::ACTIVE);
    }

    /**
     * @param bool|int $active
     * @return VendorInterface
     */
    public function setActive($active)
    {
        return $this->setData(self::ACTIVE, $active);
    }

    /**
     * @return int
     */
    public function getActiveStatus() {
        return 1;
    }

    /**
     * @return int
     */
    public function getNonActiveStatus() {
        return 0;
    }

    /**
     * Return data, used for editing form
     * @return mixed
     */
    public function getDataForForm()
    {
        $ignoreFields = $this->ignoreFormFields;
        $data = $this->getData();
        foreach ($ignoreFields as $field) {
            if (isset($data[$field])) unset($data[$field]);
        }
        return $data;
    }
}
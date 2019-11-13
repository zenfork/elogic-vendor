<?php
namespace Elogic\Vendor\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface VendorInterface extends ExtensibleDataInterface {
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return void
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return void
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param string $description
     * @return void
     */
    public function setDescription($description);

    /**
     * @return string
     */
    public function getLogo();

    /**
     * @param string $logo
     * @return void
     */
    public function setLogo($logo);

    /**
     * @return string
     */
    public function getActive();

    /**
     * Set is active
     *
     * @param int|bool $active
     * @return \Elogic\Vendor\Api\Data\VendorInterface
     */
    public function setActive($active);

    /**
     * @return int
     */
    public function getActiveStatus();

    /**
     * @return int
     */
    public function getNonActiveStatus();

    /**
     * @return mixed
     */
    public function getDataForForm();
}
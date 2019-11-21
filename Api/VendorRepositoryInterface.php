<?php
namespace Elogic\Vendor\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Elogic\Vendor\Api\Data\VendorInterface;

interface VendorRepositoryInterface
{
    /**
     * @param int $id
     * @return \Elogic\Vendor\Api\Data\VendorInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param \Elogic\Vendor\Api\Data\VendorInterface $vendor
     * @return \Elogic\Vendor\Api\Data\VendorInterface
     */
    public function save(VendorInterface $vendor);

    /**
     * @param \Elogic\Vendor\Api\Data\VendorInterface $vendor
     * @return void
     */
    public function delete(VendorInterface $vendor);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Elogic\Vendor\Api\Data\VendorSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
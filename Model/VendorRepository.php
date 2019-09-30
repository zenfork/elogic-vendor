<?php

namespace Elogic\Vendor\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Elogic\Vendor\Api\Data\VendorInterface;
use Elogic\Vendor\Api\Data\VendorSearchResultInterfaceFactory;
use Elogic\Vendor\Api\VendorRepositoryInterface;
use Elogic\Vendor\Model\ResourceModel\Vendor\CollectionFactory as VendorCollectionFactory;
use Elogic\Vendor\Model\ResourceModel\Vendor\Collection;
use Elogic\Vendor\Model\ResourceModel\Vendor as VendorResource;

class VendorRepository implements VendorRepositoryInterface
{
    /**
     * @var VendorFactory
     */
    private $vendorFactory;

    /**
     * @var VendorCollectionFactory
     */
    private $vendorCollectionFactory;

    /**
     * @var VendorSearchResultInterfaceFactory
     */
    private $searchResultFactory;
    /**
     * @var VendorResource
     */
    private $vendorResource;

    public function __construct(
        VendorFactory $vendorFactory,
        VendorCollectionFactory $vendorCollectionFactory,
        VendorSearchResultInterfaceFactory $vendorSearchResultInterfaceFactory,
        VendorResource $vendorResource
    ) {
        $this->vendorFactory = $vendorFactory;
        $this->vendorCollectionFactory = $vendorCollectionFactory;
        $this->searchResultFactory = $vendorSearchResultInterfaceFactory;
        $this->vendorResource = $vendorResource;
    }
    
    public function getById($id)
    {
        $vendor = $this->vendorFactory->create();
        $this->vendorResource->load($vendor, $id);
        if (! $vendor->getId()) {
            throw new NoSuchEntityException(__('Unable to find vendor with ID "%1"', $id));
        }
        return $vendor;
    }

    public function save(VendorInterface $vendor)
    {
        $vendor->getResource()->save($vendor);
        return $vendor;
    }

    public function delete(VendorInterface $vendor)
    {
        $vendor->getResource()->delete($vendor);
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();

        $this->addFiltersToCollection($searchCriteria, $collection);
        $this->addSortOrdersToCollection($searchCriteria, $collection);
        $this->addPagingToCollection($searchCriteria, $collection);

        $collection->load();

        return $this->buildSearchResult($searchCriteria, $collection);
    }

    private function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fields = $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $fields[] = $filter->getField();
                $conditions[] = [$filter->getConditionType() => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }
    }

    private function addSortOrdersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ((array) $searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
            $collection->addOrder($sortOrder->getField(), $direction);
        }
    }

    private function addPagingToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
    }

    private function buildSearchResult(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $searchResults = $this->searchResultFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
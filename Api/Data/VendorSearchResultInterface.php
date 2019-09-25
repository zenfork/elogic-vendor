<?php

namespace Elogic\Vendor\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface VendorSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \Elogic\Vendor\Api\Data\VendorInterface[]
     */
    public function getItems();

    /**
     * @param \Elogic\Vendor\Api\Data\VendorInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
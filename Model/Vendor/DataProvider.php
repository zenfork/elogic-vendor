<?php

namespace Elogic\Vendor\Model\Vendor;

use Elogic\Vendor\Model\ResourceModel\Vendor\CollectionFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Framework\AuthorizationInterface;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Ui\DataProvider\ModifierPoolDataProvider
{
    /**
     * @var \Elogic\Vendor\Model\ResourceModel\Vendor\Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var AuthorizationInterface
     */
    private $auth;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $vendorCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     * @param AuthorizationInterface|null $auth
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $vendorCollectionFactory,
        DataPersistorInterface $dataPersistor,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null,
        ?AuthorizationInterface $auth = null
    ) {
        $this->collection = $vendorCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
        $this->auth = $auth ?? ObjectManager::getInstance()->get(AuthorizationInterface::class);
        $this->meta = $this->prepareMeta($this->meta);
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     */
    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var $vendor \Elogic\Vendor\Api\Data\VendorInterface */
        foreach ($items as $vendor) {
            $this->loadedData[$vendor->getId()] = $vendor->getDataForForm();
        }

        $data = $this->dataPersistor->get('elogic_vendor_vendor');

        if (!empty($data)) {
            $vendor = $this->collection->getNewEmptyItem();
            $vendor->setData($data);
            $this->loadedData[$vendor->getId()] = $vendor->getData();
            $this->dataPersistor->clear('elogic_vendor_vendor');
        }

        // TODO: Rewrite getLogo(). This function should return full path to img, besides the name
        if ($vendor->getLogo()) {
            $m['logo'][0]['name'] = $vendor->getLogo();
            $m['logo'][0]['url'] = $this->getMediaUrl().$vendor->getLogo();
            $fullData = $this->loadedData;
            $this->loadedData[$vendor->getId()] = array_merge($fullData[$vendor->getId()], $m);
        }

        return $this->loadedData;
    }

    private function getMediaUrl()
    {
        return $this->storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'vendor/logos/';
    }

    /**
     * @inheritDoc
     */
   public function getMeta()
    {
        $meta = parent::getMeta();

        return $meta;
    }
}

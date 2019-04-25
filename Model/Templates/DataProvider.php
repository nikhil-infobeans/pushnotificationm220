<?php

/**
 * Infobeans PushNotification Module
 *
 * @category   Infobeans
 * @package    Infobeans_PushNotification
 * @description PushNotification Data provider class
 *
 */

namespace Infobeans\PushNotification\Model\Templates;

use Infobeans\PushNotification\Model\ResourceModel\Templates\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var array
     * @codingStandardsIgnoreStart
     */
    protected $loadedData;
    protected $storeManager;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->storeManager = $storeManager;
    }
    // @codingStandardsIgnoreEnd

    /**
     * Get data
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        
        $items = $this->collection->getItems();
        foreach ($items as $model) {
            $this->loadedData[$model->getId()] = $model->getData();
            if ($model->getLogo()) {
                $m['logo'][0]['name'] = $model->getLogo();
                $m['logo'][0]['url'] = $this->getMediaUrl().$model->getLogo();
                $fullData = $this->loadedData;
                $this->loadedData[$model->getId()] = array_merge($fullData[$model->getId()], $m);
            }
        }
        
        return $this->loadedData;
    }
    
    public function getMediaUrl()
    {
        $mediaUrl = $this->storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'pushnotification/logo/';
        return $mediaUrl;
    }
}
<?php
/**
 * InfoBeans PushNotification Extension
 *
 * @category   Infobeans
 * @package    PushNotification
 * @version    1.0.0
 * @description Show notifications templates in dropdoen under mass action
 *
 * Release with version 1.0.0
 *
 * @author      InfoBeans Technologies Limited http://www.infobeans.com/
 * @copyright   Copyright (c) 2019 InfoBeans Technologies Limited
 */

namespace Infobeans\PushNotification\Ui\Component\MassAction\Select;

class NotificationTemplate implements \Zend\Stdlib\JsonSerializable
{
    /**
     * @var \Infobeans\PushNotification\Model\ResourceModel\Templates\CollectionFactory
     */
    protected $templateCollectionFactory;
    /**
     * @var array
     */
    protected $templates;

    /**
     * @var array
     */
    protected $options;
    
    /**
     * @var array
     */
    protected $data;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var string
     */
    protected $urlPath;

    /**
     * @var string
     */
    protected $paramName;

    /**
     * @var array
     */
    protected $additionalData = [];

    /**
     * @param \Infobeans\PushNotification\Model\TemplatesFactory $templateFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $data
     */
    public function __construct(
        \Infobeans\PushNotification\Model\ResourceModel\Templates\CollectionFactory $templateCollectionFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        array $data = []
    ) {
        $this->data = $data;
        $this->urlBuilder = $urlBuilder;
        $this->templateCollectionFactory = $templateCollectionFactory;
    }

    /**
     * Get action options
     *
     * @return array
     */
    public function jsonSerialize()
    {
        if ($this->options === null) {
            $templates = $this->templateCollectionFactory->create();
            $this->prepareData();
            foreach ($templates as $templateData) {
                
                $this->options[$templateData->getId()] = [
                    'type' => 'select_template' . $templateData->getId(),
                    'label' => $templateData->getTitle(),
                ];
                
                if ($this->urlPath && $this->paramName) {
                    $this->options[$templateData->getId()]['url'] = $this->urlBuilder->getUrl(
                        $this->urlPath,
                        [$this->paramName => $templateData->getId()]
                    );
                }
 
                $this->options[$templateData->getId()] = array_merge_recursive(
                    $this->options[$templateData->getId()],
                    $this->additionalData
                );
            }

            $this->options = !empty($this->options) ? array_values($this->options) : [];
        }
        return $this->options;
    }

    /**
     * Prepare addition data for subactions
     *
     * @return void
     */
    protected function prepareData()
    {
        foreach ($this->data as $key => $value) {
            switch ($key) {
                case 'urlPath':
                    $this->urlPath = $value;
                    break;
                case 'paramName':
                    $this->paramName = $value;
                    break;
                default:
                    $this->additionalData[$key] = $value;
                    break;
            }
        }
    }
}
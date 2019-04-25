<?php
/**
 * InfoBeans PushNotification Extension
 *
 * @category   Infobeans
 * @package    PushNotification
 * @version    1.0.0
 * @description Helper File
 *
 * Release with version 1.0.0
 *
 * @author      InfoBeans Technologies Limited http://www.infobeans.com/
 * @copyright   Copyright (c) 2019 InfoBeans Technologies Limited
 */

namespace Infobeans\PushNotification\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * @SuppressWarnings(PHPMD)
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_PUSH_NOTIFICATION_SERVER_KEY = 'push_notification/general/server_key';
    const XML_PATH_PUSH_NOTIFICATION_SENDER_ID = 'push_notification/general/server_id';
    const XML_PATH_PUSH_NOTIFICATION_STATUS = 'push_notification/general/status';
    
    // @codingStandardsIgnoreStart
    /**
     * @var \Magento\Framework\HTTP\Client\Curl $curl
     */
    
    protected $curl;
    
    /**
     * @var \Infobeans\PushNotification\Model\UsersFactory $userFactory
     */
    protected $userFactory;
    
    /**
     * @var \Infobeans\PushNotification\Model\TemplatesFactory $templateFactory
     */
    
    protected $templateFactory;
    
    /**
     * @var \Magento\Framework\Filesystem\Driver\File $file
     */
    protected $file;
    
    /**
     * @var \Magento\Framework\Filesystem $filesystem
     */
    protected $filesystem;

        
    /**
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\HTTP\Client\Curl   $curl
     * @param \Infobeans\PushNotification\Model\UsersFactory $userFactory,
     * @param \Infobeans\PushNotification\Model\TemplatesFactory $templateFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Filesystem\Driver\File $file
     * 
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\HTTP\Client\Curl $curl,
        \Infobeans\PushNotification\Model\UsersFactory $userFactory,
        \Infobeans\PushNotification\Model\TemplatesFactory $templateFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Filesystem\Driver\File $file,
        \Magento\Framework\Filesystem $filesystem
        
    ) {
        parent::__construct($context);
        $this->curl = $curl;
        $this->userFactory = $userFactory;
        $this->templateFactory = $templateFactory;
        $this->storeManager = $storeManager;
        $this->file = $file;
        $this->filesystem = $filesystem;
    }

    /**
     * 
     * @param type $userId
     * @param type $templateId
     */
    public function processNotification($userId, $templateId)
    {
      $userData = $this->userFactory->create()->load($userId);
      $getTemplateData = $this->getTemplateDetails($templateId);
      $response = $this->sendNotification($userData->getToken(), $getTemplateData);
      return $response;
    }
    
    /**
     * 
     * @return string
     */
    public function getMediaUrl()
    {
        $mediaUrl = $this->storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'pushnotification/logo/';
        return $mediaUrl;
    }
    
    /**
     * Get server key from configuration
     * @return string
     */
    public function getServerKey()
    {
        $serverKey = $this->scopeConfig->getValue(
            self::XML_PATH_PUSH_NOTIFICATION_SERVER_KEY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return $serverKey;
    }
    
    /**
     * Get sender id from configuration
     * @return string
     */
    public function getSenderId()
    {
        $serverKey = $this->scopeConfig->getValue(
            self::XML_PATH_PUSH_NOTIFICATION_SENDER_ID,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return $serverKey;
    }
    
    /**
     * Get sender id from configuration
     * @return string
     */
    public function getModuleStatus()
    {
        $serverKey = $this->scopeConfig->getValue(
            self::XML_PATH_PUSH_NOTIFICATION_STATUS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return $serverKey;
    }
    
    /**
     * Get template details
     * @param type $templateId
     * @return type
     */
    public function getTemplateDetails($templateId) {
        $templateData = $this->templateFactory->create()->load($templateId);
        return $templateData;
    }

    /**
     * Send notification
     * @param type $token
     * @param type $getTemplateData
     * @return type
     */
    public function sendNotification($token, $getTemplateData) {
        $authentication = $this->getServerKey();
        $options = [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_HTTPHEADER => array(
                "Authorization: key=$authentication",
                "Content-Type: application/json"
            ),
            CURLOPT_VERBOSE => 1
        ];
        
        $this->curl->setOptions($options);

        $url = "https://fcm.googleapis.com/fcm/send";
        $params['registration_ids'] = [$token];
        $logoUrl = $this->getMediaUrl().$getTemplateData->getLogo();
        $redirectUrl = ($getTemplateData->getRedirectUrl() != '') ? $getTemplateData->getRedirectUrl() : '/';
        $params['data'] = ['title' => $getTemplateData->getTitle(), 'body' => $getTemplateData->getMessage(), 
                           'icon' => $logoUrl, 'click_action' => $redirectUrl];
        $this->curl->post($url, json_encode($params));
        $result = json_decode($this->curl->getBody());
        return $result;
    }

    public function removeLogo($logoName) {
        $mediaRootDir = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath().'pushnotification/logo/';
        if ($this->file->isExists($mediaRootDir . $logoName)) {
            $this->file->deleteFile($mediaRootDir . $logoName);
        }
        return true;
    }
}

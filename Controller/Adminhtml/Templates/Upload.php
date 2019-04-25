<?php
/**
 * InfoBeans PushNotification Extension
 *
 * @category   Infobeans
 * @package    PushNotification
 * @version    1.0.0
 * @description Upload logo
 *
 * Release with version 1.0.0
 *
 * @author      InfoBeans Technologies Limited http://www.infobeans.com/
 * @copyright   Copyright (c) 2019 InfoBeans Technologies Limited
 */

namespace Infobeans\PushNotification\Controller\Adminhtml\Templates;

use Magento\Framework\Controller\ResultFactory;

class Upload extends \Magento\Backend\App\Action
{
    /**
     * @var \Infobeans\PushNotification\Model\ImageUploader $imageUploader
     */
    
    public $imageUploader;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Infobeans\PushNotification\Model\ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Infobeans_PushNotification::save');
    }

    public function execute()
    {
        try {
            $files = $this->getRequest()->getFiles();
            $fileName = $files['logo']['name'];
            $result = $this->imageUploader->saveFileToTmpDir('logo', $fileName);
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
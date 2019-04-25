<?php
/**
 * InfoBeans PushNotification Extension
 *
 * @category   Infobeans
 * @package    PushNotification
 * @version    1.0.0
 * @description Send push notification
 *
 * Release with version 1.0.0
 *
 * @author      InfoBeans Technologies Limited http://www.infobeans.com/
 * @copyright   Copyright (c) 2019 InfoBeans Technologies Limited
 */

namespace Infobeans\PushNotification\Controller\Adminhtml\Manageusers;

class Selectnotification extends \Magento\Backend\App\Action
{
    /**
     * @var \Infobeans\PushNotification\Helper $notifyhelper
     */
    protected $notifyhelper;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter $filter
     */
    protected $filter;

    /**
     * @var \Infobeans\PushNotification\Model\ResourceModel\Users\CollectionFactory
     */
    protected $collectionFactory;
    

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Infobeans\PushNotification\Helper\Data $notifyhelper
     * @param \Infobeans\PushNotification\Model\ResourceModel\Users\CollectionFactory $collectionFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Infobeans\PushNotification\Helper\Data $notifyhelper,
        \Infobeans\PushNotification\Model\ResourceModel\Users\CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
        $this->notifyhelper = $notifyhelper;
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Execute controller
     * @return Magento\Framework\Controller\ResultFactor
     */
    public function execute()
    {
        $moduleStatus = $this->notifyhelper->getModuleStatus();
        
        if(!$moduleStatus) {
            $this->messageManager->addError(__("Please enable the module"));
            $resultRedirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setPath('*/*/index');
            return $resultRedirect;
        }
        
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $templateId = $this->getRequest()->getParam('notificationtemplate');

        $notificationSent = 0;
        foreach ($collection->getAllIds() as $user) {
            $response = $this->notifyhelper->processNotification($user, $templateId);            
            $notificationSent++;
        }
        
        if ($notificationSent) {
            $this->messageManager->addSuccess(__('A total of %1 notification(s) were send.', $notificationSent));
        }

        $resultRedirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('*/*/index');
        return $resultRedirect;
    }
}

<?php
/**
 * InfoBeans PushNotification Extension
 *
 * @category   Infobeans
 * @package    PushNotification
 * @version    1.0.0
 * @description Mass delete
 *
 * Release with version 1.0.0
 *
 * @author      InfoBeans Technologies Limited http://www.infobeans.com/
 * @copyright   Copyright (c) 2019 InfoBeans Technologies Limited
 */

namespace Infobeans\PushNotification\Controller\Adminhtml\Manageusers;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Infobeans\PushNotification\Model\ResourceModel\Users\CollectionFactory;

/**
 * Class MassDelete
 */
class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    protected $filter;

    /** Infobeans\PushNotification\Model\ResourceModel\Users\CollectionFactory
     * @var CollectionFactory
     */
    protected $collectionFactory;
    
    /**
     * @var \Infobeans\PushNotification\Helper $notifyhelper
     */
    protected $notifyhelper;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param \Infobeans\PushNotification\Helper\Data $notifyhelper
     */
    public function __construct(
        Context $context, 
        Filter $filter,
        CollectionFactory $collectionFactory,
        \Infobeans\PushNotification\Helper\Data $notifyhelper 
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->notifyhelper = $notifyhelper;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $user) {
                $user->delete();
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 user(s) have been deleted.', $collectionSize));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        
        return $resultRedirect->setPath('*/*/');
    }
}

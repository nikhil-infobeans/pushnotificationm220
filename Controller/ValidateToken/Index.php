<?php
/**
 * InfoBeans PushNotification Extension
 *
 * @category   Infobeans
 * @package    PushNotification
 * @version    1.0.0
 * @description Saved token 
 *
 * Release with version 1.0.0
 *
 * @author      InfoBeans Technologies Limited http://www.infobeans.com/
 * @copyright   Copyright (c) 2019 InfoBeans Technologies Limited
 */

namespace Infobeans\PushNotification\Controller\ValidateToken;


class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;
    
    /**
     * @var \Infobeans\PushNotification\Model\UsersFactory $userFactory
     */
    protected $userFactory;
    // @codingStandardsIgnoreEnd
    
    /**
     * @param Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Infobeans\PushNotification\Model\UsersFactory $userFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
    
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->userFactory = $userFactory;
    }

    public function execute()
    {
        $jsonResult = $this->resultJsonFactory->create();
        $reponseArray = ["success" => false];
        if($this->getRequest()->isXmlHttpRequest()) {
            $token = $this->getRequest()->getParam("token");
            $broserType = $this->getRequest()->getParam("broserType");
            $userModel = $this->userFactory->create();
            $userData = $userModel->load($token, 'token'); 
            
           if(empty($userData->getData())){
                $userModel->setToken($token);
                $userModel->setSubscribedFrom($broserType);
                $userModel->save();
                $reponseArray = ["success" => true, 'message' => "Token saved successfully."];
            }
        }
        else{
            $reponseArray['message'] = "Invalid request";
        }
        return $jsonResult->setData($reponseArray);
    }
}

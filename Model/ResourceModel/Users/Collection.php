<?php
/**
 * InfoBeans PushNotification Extension
 *
 * @category   Infobeans
 * @package    PushNotification
 * @version    1.0.0
 * @description Registered user collection
 *
 * Release with version 1.0.0
 *
 * @author      InfoBeans Technologies Limited http://www.infobeans.com/
 * @copyright   Copyright (c) 2019 InfoBeans Technologies Limited
 */

namespace Infobeans\PushNotification\Model\ResourceModel\Users;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

        /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Infobeans\PushNotification\Model\Users::class, \Infobeans\PushNotification\Model\ResourceModel\Users::class);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        
        return $this;
    }

}

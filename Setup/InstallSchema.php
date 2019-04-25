<?php
/**
 * InfoBeans PushNotification Extension
 *
 * @category   Infobeans
 * @package    PushNotification
 * @version    1.0.0
 * @description Module Install script
 *
 * Release with version 1.0.0
 *
 * @author      InfoBeans Technologies Limited http://www.infobeans.com/
 * @copyright   Copyright (c) 2019 InfoBeans Technologies Limited
 */

namespace Infobeans\PushNotification\Setup;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
    * {@inheritdoc}
    * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
    */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
          /**
           * Create notification_templates Table
           */
          $templateTable = $setup->getConnection()
              ->newTable($setup->getTable('notification_templates'))
              ->addColumn(
                  'id',
                  \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                  null,
                  ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                  'ID'
              )
              ->addColumn(
                  'title',
                  \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                  255,
                  ['nullable' => true],
                    'Template Title'
              )->addColumn(
                  'message',
                  \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                  255,
                  ['nullable' => true],
                    'Template Message'
              )->addColumn(
                  'redirect_url',
                  \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                  255,
                  ['nullable' => true],
                    'Redirect Url'
              )->addColumn(
                  'logo',
                  \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                  255,
                  ['nullable' => true],
                    'Template Logo'
              )->addColumn(
                   'created_at',
                   \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                   null,
                   ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                    'Created At'
              )->addColumn(
                   'updated_at',
                   \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                   null,
                   ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                   'Updated At'
              )->setComment("Push Notification Templates");
          $setup->getConnection()->createTable($templateTable);
          
          /**
           * Create notification_users Table
           */
          $notificationUsers = $setup->getConnection()
              ->newTable($setup->getTable('notification_users'))
              ->addColumn(
                  'id',
                  \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                  null,
                  ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                  'ID'
              )
              ->addColumn(
                  'token',
                  \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                  255,
                  ['nullable' => true],
                    'Browser Token'
              )->addColumn(
                  'subscribed_from',
                  \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                  255,
                  ['nullable' => true],
                    'Subscribed From'
              )->addColumn(
                   'created_at',
                   \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                   null,
                   ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                    'Created At'
              )->addColumn(
                   'updated_at',
                   \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                   null,
                   ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                   'Updated At'
              )->setComment("Push Notification Users");
          $setup->getConnection()->createTable($notificationUsers);
      }
}
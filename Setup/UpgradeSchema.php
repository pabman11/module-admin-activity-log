<?php
/**
 * KiwiCommerce
 *
 * Do not edit or add to this file if you wish to upgrade to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please contact us https://kiwicommerce.co.uk/contacts.
 *
 * @category   KiwiCommerce
 * @package    MageOS_AdminActivityLog
 * @copyright  Copyright (C) 2018 Kiwi Commerce Ltd (https://kiwicommerce.co.uk/)
 * @license    https://kiwicommerce.co.uk/magento2-extension-license/
 */

namespace MageOS\AdminActivityLog\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * Class UpgradeSchema
 * @package MageOS\AdminActivityLog\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * Upgrade DB schema for a module
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '0.0.2') < 0) {
            $tableName = $setup->getTable('kiwicommerce_activity');

            //TODO: Check if the table already exists
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                $columns = [
                    'forwarded_ip' => [
                        'type' => Table::TYPE_TEXT,
                        'length' => 32,
                        'nullable' => true,
                        'comment' => __('Real ip address if visitor used proxy'),
                        'after' => 'remote_ip'
                    ]
                ];

                $connection = $setup->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($tableName, $name, $definition);
                }
            }
        }

        if (version_compare($context->getVersion(), '0.1.2') < 0) {
            $tableName = $setup->getTable('kiwicommerce_activity');

            //TODO: Check if the table already exists
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                $columns = [
                    'scope' => [
                        'type' => Table::TYPE_TEXT,
                        'length' => 15,
                        'nullable' => true,
                        'comment' => __('Scope of activity'),
                        'after' => 'store_id'
                    ]
                ];

                $connection = $setup->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($tableName, $name, $definition);
                }
            }
        }

        if (version_compare($context->getVersion(), '0.1.3') < 0) {
            /**
             * Create table 'kiwicommerce_login_activity'
             */
            $table = $setup->getConnection()->newTable(
                $setup->getTable('kiwicommerce_login_activity')
            )->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Primary key for the table'
            )->addColumn(
                'username',
                Table::TYPE_TEXT,
                40,
                ['nullable' => false, 'default' => ''],
                'Admin username'
            )->addColumn(
                'name',
                Table::TYPE_TEXT,
                64,
                ['nullable' => false, 'default' => ''],
                'Full name of admin'
            )->addColumn(
                'remote_ip',
                Table::TYPE_TEXT,
                32,
                ['nullable' => false, 'default' => ''],
                'IP address of logged in admin user'
            )->addColumn(
                'forwarded_ip',
                Table::TYPE_TEXT,
                32,
                ['nullable' => false, 'default' => ''],
                'Real ip address if visitor used proxy'
            )->addColumn(
                'user_agent',
                Table::TYPE_TEXT,
                '',
                ['nullable' => false, 'default' => ''],
                'Store browser’s user agent'
            )->addColumn(
                'location',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false, 'default' => ''],
                'Location of visitor'
            )->addColumn(
                'type',
                Table::TYPE_TEXT,
                8,
                ['nullable' => false, 'default' => ''],
                'Is it Login/Logout?'
            )->addColumn(
                'status',
                Table::TYPE_BOOLEAN,
                '',
                ['nullable' => true],
                '0 = Faild, 1= Success'
            )->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'The date when the activity was created'
            )->setComment(
                'Log all login/logout activity of admin user'
            );
            $setup->getConnection()->createTable($table);
        }

        if (version_compare($context->getVersion(), '0.1.4') < 0) {
            $tableName = $setup->getTable('kiwicommerce_login_activity');
            $connection = $setup->getConnection();

            //TODO: Check if the table already exists
            if ($connection->isTableExists($tableName) == true) {
                $connection->addColumn($tableName, 'remarks', [
                    'type' => Table::TYPE_TEXT,
                    'length' => '',
                    'nullable' => false,
                    'default' => '',
                    'comment' => __('Reason for failed'),
                    'after' => 'status'
                ]);

                $connection->dropColumn($tableName, 'location', $schemaName = null);
            }
        }

        if (version_compare($context->getVersion(), '0.1.5') < 0) {
            $tableName = $setup->getTable('kiwicommerce_activity_log');

            //TODO: Check if the table already exists
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                $definition = [
                    'type' => Table::TYPE_TEXT,
                    'length' => 255
                ];

                $setup->getConnection()->addColumn($tableName, 'field_name', $definition);
            }
        }

        if (version_compare($context->getVersion(), '0.1.6') < 0) {
            $tableName = $setup->getTable('kiwicommerce_activity');
            $connection = $setup->getConnection();

            //TODO: Check if the table already exists
            if ($connection->isTableExists($tableName) == true) {
                $connection->addColumn($tableName, 'fullaction', [
                    'type' => Table::TYPE_TEXT,
                    'length' => 200,
                    'nullable' => false,
                    'default' => '',
                    'comment' => __('Full Action Name'),
                    'after' => 'module'
                ]);
            }
        }

        $setup->endSetup();
    }
}

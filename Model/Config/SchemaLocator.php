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

namespace MageOS\AdminActivityLog\Model\Config;

use Magento\Framework\Config\SchemaLocatorInterface;
use Magento\Framework\Module\Dir;
use Magento\Framework\Module\Dir\Reader;

/**
 * Class SchemaLocator
 * @package MageOS\AdminActivityLog\Model\Config
 */
class SchemaLocator implements SchemaLocatorInterface
{
    /**
     * XML schema for config file.
     */
    public const CONFIG_FILE_SCHEMA = 'adminactivity.xsd';

    /**
     * Path to corresponding XSD file with validation rules for merged config
     * @var string
     */
    public $schema = null;

    /**
     * Path to corresponding XSD file with validation rules for separate config files
     * @var string
     */
    public $perFileSchema = null;

    /**
     * SchemaLocator constructor.
     * @param Reader $moduleReader
     */
    public function __construct(Reader $moduleReader)
    {
        $configDir = $moduleReader->getModuleDir(Dir::MODULE_ETC_DIR, 'MageOS_AdminActivityLog');
        $this->schema = $configDir . DIRECTORY_SEPARATOR . self::CONFIG_FILE_SCHEMA;
        $this->perFileSchema = $configDir . DIRECTORY_SEPARATOR . self::CONFIG_FILE_SCHEMA;
    }

    /**
     * Get schema
     * @return string
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * Get file schema
     * @return string
     */
    public function getPerFileSchema()
    {
        return $this->perFileSchema;
    }
}

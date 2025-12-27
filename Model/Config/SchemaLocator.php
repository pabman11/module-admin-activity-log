<?php
/**
 * MageOS
 *
 * @category   MageOS
 * @package    MageOS_AdminActivityLog
 * @copyright  Copyright (C) 2018 Kiwi Commerce Ltd (https://kiwicommerce.co.uk/)
 * @copyright  Copyright (C) 2025 MageOS (https://mage-os.org/)
 * @license    https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

declare(strict_types=1);

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
    public const CONFIG_FILE_SCHEMA = 'adminactivity.xsd';

    /**
     * Path to corresponding XSD file with validation rules for merged config
     * @var string
     */
    protected readonly string $schema;

    /**
     * Path to corresponding XSD file with validation rules for separate config files
     * @var string
     */
    protected readonly string $perFileSchema;

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
    public function getSchema(): string
    {
        return $this->schema;
    }

    /**
     * Get file schema
     * @return string
     */
    public function getPerFileSchema(): string
    {
        return $this->perFileSchema;
    }
}

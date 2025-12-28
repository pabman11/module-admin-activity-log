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

namespace MageOS\AdminActivityLog\Api;

use Magento\Framework\DataObject;

/**
 * Service interface for tracking field changes in admin activity log
 *
 * Provides methods to get tracked field data for add/edit/delete operations.
 * Replaces Helper\TrackField for proper service-oriented architecture.
 *
 * @api
 */
interface FieldTrackerInterface
{
    /**
     * Get global skip edit fields from XML config
     *
     * @return string[]
     */
    public function getSkipEditFieldData(): array;

    /**
     * Get skip fields from method name or array
     *
     * Accepts either:
     * - string method name (legacy): calls the method dynamically
     * - array of field names (new): returns the array directly
     *
     * @param string|array<string> $methodOrFields Method name or array of fields
     * @return string[]
     */
    public function getFields(string|array $methodOrFields): array;

    /**
     * Get added activity data
     *
     * @param DataObject $model The model being saved
     * @param string|array<string> $methodOrFields Method name or array of skip fields
     * @return array<string, array{old_value: string, new_value: string}>
     */
    public function getAddData(DataObject $model, string|array $methodOrFields): array;

    /**
     * Get edited activity data
     *
     * @param DataObject $model The model being edited
     * @param string|array<string> $methodOrFields Method name or array of skip fields
     * @return array<string, array{old_value: string, new_value: string}>
     */
    public function getEditData(DataObject $model, string|array $methodOrFields): array;

    /**
     * Get deleted activity data
     *
     * @param DataObject $model The model being deleted
     * @param string|array<string> $methodOrFields Method name or array of skip fields
     * @return array<string, array{old_value: string, new_value: string}>
     */
    public function getDeleteData(DataObject $model, string|array $methodOrFields): array;

    /**
     * Get special "wildcard" field data (e.g., product quantity)
     *
     * @param DataObject $model The model
     * @param string|array<string> $methodOrFields Method name or array of skip fields
     * @return array<string, array{old_value: string, new_value: string}>
     */
    public function getWildCardData(DataObject $model, string|array $methodOrFields): array;

    /**
     * Validate if a field value should be skipped
     *
     * @param DataObject $model The model
     * @param string $key Field key
     * @param mixed $value Field value
     * @param array<string> $skipFields Fields to skip
     * @return bool True if the field should be skipped
     */
    public function validateValue(DataObject $model, string $key, mixed $value, array $skipFields): bool;

    /**
     * Truncate a value if it exceeds the maximum length
     *
     * @param mixed $value The value to truncate
     * @return string The truncated string value
     */
    public function truncateValue(mixed $value): string;
}

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

namespace MageOS\AdminActivityLog\Api\Activity;

use Magento\Framework\DataObject;

/**
 * Interface ModelInterface
 * @package MageOS\AdminActivityLog\Api\Activity
 */
interface ModelInterface
{
    /**
     * @param DataObject $model
     * @return mixed
     */
    public function getOldData(DataObject $model);

    /**
     * @param DataObject $model
     * @param array<string, string> $fieldArray
     * @return array{}|array<string, array{
     *       old_value: string,
     *       new_value: string
     *   }>
     */
    public function getEditData(DataObject $model, array $fieldArray);
}

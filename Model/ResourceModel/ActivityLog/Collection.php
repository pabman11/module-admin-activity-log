<?php
/**
 * MageOS
 *
 * @category   MageOS
 * @package    MageOS_AdminActivityLog
 * @copyright  Copyright (C) 2018 Kiwi Commerce Ltd (https://kiwicommerce.co.uk/)
 * @copyright  Copyright (C) 2024 MageOS (https://mage-os.org/)
 * @license    https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

declare(strict_types=1);

namespace MageOS\AdminActivityLog\Model\ResourceModel\ActivityLog;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use MageOS\AdminActivityLog\Model\ResourceModel\ActivityLog;

/**
 * Class Collection
 * @package MageOS\AdminActivityLog\Model\ResourceModel\ActivityLog
 */
class Collection extends AbstractCollection
{
    public function _construct(): void
    {
        $this->_init(
            \MageOS\AdminActivityLog\Model\ActivityLog::class,
            ActivityLog::class
        );
    }
}

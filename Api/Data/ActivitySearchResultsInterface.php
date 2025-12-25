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

namespace MageOS\AdminActivityLog\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;
use MageOS\AdminActivityLog\Model\Activity;

/**
 * Interface LogSearchResultsInterface
 * @package KiwiCommerce\EnhancedSMTP\Api\Data
 */
interface ActivitySearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get admin activity list.
     * @return Activity[]
     * @api
     */
    public function getItems();

    /**
     * Set admin activity list.
     * @param Activity[] $items
     * @return $this
     * @api
     */
    public function setItems(array $items);
}

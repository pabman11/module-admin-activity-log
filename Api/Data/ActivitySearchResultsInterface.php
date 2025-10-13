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

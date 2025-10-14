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

namespace MageOS\AdminActivityLog\Api;

use Magento\User\Model\User;
use MageOS\AdminActivityLog\Model\ResourceModel\Login\Collection;

/**
 * Interface LoginRepositoryInterface
 * @package MageOS\AdminActivityLog\Api
 */
interface LoginRepositoryInterface
{
    /**
     * Set login data
     * @param $status
     * @param $type
     * @return bool
     */
    public function addLog($status, $type): bool;

    /**
     * Get all admin activity data before date
     * @param $endDate
     * @return Collection
     */
    public function getListBeforeDate($endDate): Collection;

    /**
     * Set login user
     * @param User $user
     * @return LoginRepositoryInterface
     */
    public function setUser($user): LoginRepositoryInterface;
}

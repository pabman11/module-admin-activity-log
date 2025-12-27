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
     * @param int $status
     * @param string $type
     * @return bool
     */
    public function addLog(int $status, string $type): bool;

    /**
     * Get all admin activity data before date
     * @param string $endDate
     * @return Collection
     */
    public function getListBeforeDate(string $endDate): Collection;

    /**
     * Set login user
     * @param User $user
     * @return LoginRepositoryInterface
     */
    public function setUser(User $user): LoginRepositoryInterface;
}

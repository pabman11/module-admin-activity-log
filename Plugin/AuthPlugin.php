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

namespace MageOS\AdminActivityLog\Plugin;

use Magento\Backend\Model\Auth;
use MageOS\AdminActivityLog\Api\LoginRepositoryInterface;
use MageOS\AdminActivityLog\Helper\Data as Helper;

/**
 * Class Auth
 * @package MageOS\AdminActivityLog\Plugin
 */
class AuthPlugin
{
    public function __construct(
        private readonly Helper $helper,
        private readonly LoginRepositoryInterface $loginRepository
    ) {
    }

    /**
     * Track admin logout activity
     */
    public function aroundLogout(Auth $auth, callable $proceed): void
    {
        if ($this->helper->isLoginEnable()) {
            $user = $auth->getAuthStorage()->getUser();
            $this->loginRepository->setUser($user)->addLogoutLog();
        }

        $proceed();
    }
}

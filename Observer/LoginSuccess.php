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

namespace MageOS\AdminActivityLog\Observer;

use Magento\Framework\Event\Observer;
use MageOS\AdminActivityLog\Api\LoginRepositoryInterface;
use MageOS\AdminActivityLog\Helper\Data as Helper;

class LoginSuccess extends AbstractActivityObserver
{
    public function __construct(
        Helper $helper,
        private readonly LoginRepositoryInterface $loginRepository
    ) {
        parent::__construct($helper);
    }

    protected function isEnabled(): bool
    {
        return $this->helper->isLoginEnable();
    }

    protected function process(Observer $observer): void
    {
        $this->loginRepository->setUser($observer->getUser())->addSuccessLog();
    }
}

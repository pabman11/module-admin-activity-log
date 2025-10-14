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

namespace MageOS\AdminActivityLog\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use MageOS\AdminActivityLog\Api\LoginRepositoryInterface;
use MageOS\AdminActivityLog\Helper\Benchmark;
use MageOS\AdminActivityLog\Helper\Data as Helper;

/**
 * Class LoginSuccess
 * @package MageOS\AdminActivityLog\Observer
 */
class LoginSuccess implements ObserverInterface
{
    /**
     * LoginSuccess constructor.
     * @param Helper $helper
     * @param LoginRepositoryInterface $loginRepository
     * @param Benchmark $benchmark
     */
    public function __construct(
        protected readonly Helper $helper,
        protected readonly LoginRepositoryInterface $loginRepository,
        protected readonly Benchmark $benchmark
    ) {
    }

    /**
     * Login success
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $this->benchmark->start(__METHOD__);
        if (!$this->helper->isLoginEnable()) {
            return;
        }

        $this->loginRepository
            ->setUser($observer->getUser())
            ->addSuccessLog();
        $this->benchmark->end(__METHOD__);
    }
}

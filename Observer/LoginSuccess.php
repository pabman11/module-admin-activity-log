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
     * @var Helper
     */
    public $helper;

    /**
     * @var LoginRepositoryInterface
     */
    public $loginRepository;

    /**
     * @var Benchmark
     */
    public $benchmark;

    /**
     * LoginSuccess constructor.
     * @param Helper $helper
     * @param LoginRepositoryInterface $loginRepository
     * @param Benchmark $benchmark
     */
    public function __construct(
        Helper $helper,
        LoginRepositoryInterface $loginRepository,
        Benchmark $benchmark
    ) {
        $this->helper = $helper;
        $this->loginRepository = $loginRepository;
        $this->benchmark = $benchmark;
    }

    /**
     * Login success
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $this->benchmark->start(__METHOD__);
        if (!$this->helper->isLoginEnable()) {
            return $observer;
        }

        $this->loginRepository
            ->setUser($observer->getUser())
            ->addSuccessLog();
        $this->benchmark->end(__METHOD__);
    }
}

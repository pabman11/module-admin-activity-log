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
use Magento\User\Model\User;
use MageOS\AdminActivityLog\Api\LoginRepositoryInterface;
use MageOS\AdminActivityLog\Helper\Benchmark;
use MageOS\AdminActivityLog\Helper\Data as Helper;

/**
 * Class LoginFailed
 * @package MageOS\AdminActivityLog\Observer
 */
class LoginFailed implements ObserverInterface
{
    /**
     * @var Helper
     */
    public $helper;

    /**
     * @var User
     */
    public $user;

    /**
     * @var LoginRepositoryInterface
     */
    public $loginRepository;

    /**
     * @var Benchmark
     */
    public $benchmark;

    /**
     * LoginFailed constructor.
     * @param Helper $helper
     * @param User $user
     * @param LoginRepositoryInterface $loginRepository
     * @param Benchmark $benchmark
     */
    public function __construct(
        Helper $helper,
        User $user,
        LoginRepositoryInterface $loginRepository,
        Benchmark $benchmark
    ) {
        $this->helper = $helper;
        $this->user = $user;
        $this->loginRepository = $loginRepository;
        $this->benchmark = $benchmark;
    }

    /**
     * Login failed
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $this->benchmark->start(__METHOD__);
        if (!$this->helper->isLoginEnable()) {
            return $observer;
        }

        $user = null;
        if ($observer->getUserName()) {
            $user = $this->user->loadByUsername($observer->getUserName());
        }

        $this->loginRepository
            ->setUser($user)
            ->addFailedLog($observer->getException()->getMessage());
        $this->benchmark->end(__METHOD__);
    }
}

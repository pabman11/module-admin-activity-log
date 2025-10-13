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

namespace MageOS\AdminActivityLog\Plugin;

use MageOS\AdminActivityLog\Api\LoginRepositoryInterface;
use MageOS\AdminActivityLog\Helper\Benchmark;
use MageOS\AdminActivityLog\Helper\Data as Helper;

/**
 * Class Auth
 * @package MageOS\AdminActivityLog\Plugin
 */
class Auth
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
     * Auth constructor.
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
     * Track admin logout activity
     * @param \Magento\Backend\Model\Auth $auth
     * @param callable $proceed
     * @return mixed
     */
    public function aroundLogout(\Magento\Backend\Model\Auth $auth, callable $proceed)
    {
        $this->benchmark->start(__METHOD__);
        if ($this->helper->isLoginEnable()) {
            $user = $auth->getAuthStorage()->getUser();
            $this->loginRepository->setUser($user)->addLogoutLog();
        }
        $result = $proceed();
        $this->benchmark->end(__METHOD__);
        return $result;
    }
}

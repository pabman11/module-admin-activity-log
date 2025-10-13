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

namespace MageOS\AdminActivityLog\Test\Unit\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value\Interceptor;
use Magento\Framework\App\Helper\Context;
use MageOS\AdminActivityLog\Helper\Data;
use MageOS\AdminActivityLog\Model\Config;
use PHPUnit\Framework\TestCase;

/**
 * Class DataTest
 * @package MageOS\AdminActivityLog\Test\Unit\Helper
 */
class DataTest extends TestCase
{
    /**
     * @requires PHP 7.0
     */
    public function setUp()
    {
        $this->context = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->scopeConfig = $this->getMockBuilder(ScopeConfigInterface::class)
            ->getMockForAbstractClass();

        $this->scopeConfig->expects($this->any())
            ->method('isSetFlag')
            ->willReturn(true);

        $this->context->expects($this->any())
            ->method('getScopeConfig')
            ->willReturn($this->scopeConfig);

        $this->config = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->helper = new Data(
            $this->context,
            $this->config
        );
    }

    /**
     * @requires PHP 7.0
     */
    public function testIsEnable()
    {
        $this->assertSame(true, $this->helper->isEnable());
    }

    /**
     * @requires PHP 7.0
     */
    public function testIsLoginEnable()
    {
        $this->assertSame(true, $this->helper->isLoginEnable());
    }

    /**
     * @requires PHP 7.0
     */
    public function testIsWildCardModel()
    {
        $notwildcardmethod = Data::isWildCardModel(
            Context::class
        );
        $this->assertSame(false, $notwildcardmethod);

        $notwildcardmethod = Data::isWildCardModel(
            Interceptor::class
        );
        $this->assertSame(true, $notwildcardmethod);
    }
}

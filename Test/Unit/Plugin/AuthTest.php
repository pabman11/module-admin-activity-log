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

namespace MageOS\AdminActivityLog\Test\Unit\Plugin;

use Magento\Backend\Model\Auth\StorageInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\User\Model\User;
use MageOS\AdminActivityLog\Api\LoginRepositoryInterface;
use MageOS\AdminActivityLog\Helper\Data;
use MageOS\AdminActivityLog\Plugin\Auth;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class AuthTest
 * @package MageOS\AdminActivityLog\Test\Unit\Plugin
 */
class AuthTest extends TestCase
{
    public $authMock;

    public $authStorageMock;

    public $helperMock;

    public $loginRepositoryMock;

    public $user;

    /**
     * @requires PHP 7.0
     */
    public function setUp()
    {
        $this->authMock = $this->getMockBuilder(\Magento\Backend\Model\Auth::class)
            ->setMethods(['getAuthStorage'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->authStorageMock = $this->getMockBuilder(StorageInterface::class)
            ->setMethods([
                'getUser',
                'processLogin',
                'isLoggedIn',
                'prolong',
                'processLogout'

            ])
            ->disableOriginalConstructor()
            ->getMock();

        $this->helperMock = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->loginRepositoryMock = $this->getMockBuilder(
            LoginRepositoryInterface
            ::class
        )
            ->setMethods(['setUser', 'addLog', 'getListBeforeDate'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->user = $this->getMockBuilder(User::class)
            ->setMethods(['addLogoutLog'])
            ->disableOriginalConstructor()
            ->getMock();

        $objectManager = new ObjectManager($this);

        $this->authTest = $objectManager->getObject(
            Auth::class,
            [
                'helper' => $this->helperMock,
                'loginRepository' => $this->loginRepositoryMock

            ]
        );
    }

    /**
     * @requires PHP 7.0
     */
    public function testAroundLogout()
    {
        $this->authMock
            ->expects($this->once())
            ->method('getAuthStorage')
            ->willReturn($this->authStorageMock);

        $this->authStorageMock
            ->expects($this->once())
            ->method('getUser')
            ->willReturn('user');

        $this->helperMock
            ->expects($this->once())
            ->method('isLoginEnable')
            ->willReturn($this->loginRepositoryMock);

        $this->loginRepositoryMock
            ->expects($this->once())
            ->method('setUser')
            ->with('user')
            ->willReturn($this->user);

        $this->user
            ->expects($this->once())
            ->method('addLogoutLog')
            ->willReturn('LogoutLog');

        $callbackMock = $this->getMockBuilder(stdClass::class)
            ->setMethods(['__invoke'])
            ->getMock();

        $callbackMock->expects($this->once())->method('__invoke');

        $this->authTest->aroundLogout($this->authMock, $callbackMock);
    }

    /**
     * @requires PHP 7.0
     */
    public function testAroundLogoutIsLoginEnableFalse()
    {
        $this->helperMock
            ->expects($this->once())
            ->method('isLoginEnable')
            ->willReturn(false);

        $callbackMock = $this->getMockBuilder(stdClass::class)
            ->setMethods(['__invoke'])
            ->getMock();

        $callbackMock
            ->expects($this->once())
            ->method('__invoke');
        $this->assertNull($this->authTest->aroundLogout($this->authMock, $callbackMock));
    }
}

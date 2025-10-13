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

namespace MageOS\AdminActivityLog\Test\Unit\Plugin\User;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\User\Model\User;
use MageOS\AdminActivityLog\Plugin\User\Delete;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class DeleteTest
 * @package MageOS\AdminActivityLog\Test\Unit\Plugin
 */
class DeleteTest extends TestCase
{
    public $delete;

    public $userMock;

    public $userResourceMock;

    /**
     * @requires PHP 7.0
     */
    public function setUp()
    {
        $this->userResourceMock = $this->getMockBuilder(\Magento\User\Model\ResourceModel\User::class)
            ->setMethods([])
            ->disableOriginalConstructor()
            ->getMock();

        $this->userMock = $this->getMockBuilder(User::class)
            ->setMethods(['load', 'getId', 'afterDelete'])
            ->disableOriginalConstructor()
            ->getMock();

        $objectManager = new ObjectManager($this);

        $this->delete = $objectManager->getObject(
            Delete::class,
            []
        );
    }

    /**
     * @requires PHP 7.0
     */
    public function testaroundDelete()
    {
        $this->userMock
            ->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $this->userMock->expects($this->once())
            ->method('load')
            ->willReturn($this->userMock);

        $this->userMock->expects($this->once())
            ->method('afterDelete')
            ->willReturnSelf();

        $callbackMock = $this->getMockBuilder(stdClass::class)
            ->setMethods(['__invoke'])
            ->getMock();

        $callbackMock
            ->expects($this->once())
            ->method('__invoke');

        $this->delete->aroundDelete($this->userResourceMock, $callbackMock, $this->userMock);
    }
}

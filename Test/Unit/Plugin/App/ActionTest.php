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

namespace MageOS\AdminActivityLog\Test\Unit\Plugin\App;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Interception\InterceptorInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use MageOS\AdminActivityLog\Model\Processor;
use MageOS\AdminActivityLog\Plugin\App\Action;
use PHPUnit\Framework\TestCase;

/**
 * Class ActionTest
 * @package MageOS\AdminActivityLog\Test\Unit\Plugin\App
 */
class ActionTest extends TestCase
{

    public $processorMock;

    public $controllerMock;

    public $requestMock;

    /**
     * @requires PHP 7.0
     */
    public function setUp()
    {
        $this->processorMock = $this->getMockBuilder(Processor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->controllerMock = $this->getMockBuilder(InterceptorInterface::class)
            ->setMethods(['getRequest', '___callParent'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestMock = $this->getMockBuilder(Http::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getActionName',
                'getFullActionName',
                'getModuleName'
            ])
            ->getMock();

        $objectManager = new ObjectManager($this);

        $this->actionTest = $objectManager->getObject(
            Action::class,
            [
                'processor' => $this->processorMock
            ]
        );
    }

    /**
     * @requires PHP 7.0
     */
    public function testBeforeDispatch()
    {
        $this->controllerMock
            ->expects($this->exactly(3))
            ->method('getRequest')
            ->willReturn($this->requestMock);

        $this->requestMock
            ->expects($this->once())
            ->method('getActionName')
            ->willReturn('action');

        $this->requestMock
            ->expects($this->once())
            ->method('getFullActionName')
            ->willReturn('fullaction');

        $this->requestMock
            ->expects($this->once())
            ->method('getModuleName')
            ->willReturn('module');

        $this->processorMock->expects($this->once())
            ->method('init')
            ->with('fullaction', 'action')
            ->willReturnSelf();

        $this->processorMock->expects($this->once())
            ->method('addPageVisitLog')
            ->with('module')
            ->willReturnSelf();

        $this->assertNull($this->actionTest->beforeDispatch($this->controllerMock));
    }
}

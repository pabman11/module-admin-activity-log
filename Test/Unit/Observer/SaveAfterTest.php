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

namespace MageOS\AdminActivityLog\Test\Unit\Observer;

use Magento\Framework\DataObject;
use Magento\Framework\Event;
use Magento\Framework\Event\Observer;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use MageOS\AdminActivityLog\Helper\Data;
use MageOS\AdminActivityLog\Model\Config;
use MageOS\AdminActivityLog\Model\Processor;
use MageOS\AdminActivityLog\Observer\SaveAfter;
use PHPUnit\Framework\TestCase;

class SaveAfterTest extends TestCase
{
    public $saveAfter;

    public $processorMock;

    public $helperMock;

    public $observerMock;

    public $eventMock;

    public $objectMock;

    public $configMock;

    public $eventConfig = ['action' => 'massCancel', 'module' => 'catalog_products'];

    /**
     * @requires PHP 7.0
     */
    public function setUp()
    {
        $this->processorMock = $this->getMockBuilder(Processor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->helperMock = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->observerMock = $this
            ->getMockBuilder(Observer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->eventMock = $this
            ->getMockBuilder(Event::class)
            ->setMethods(['getObject'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectMock = $this
            ->getMockBuilder(DataObject::class)
            ->setMethods(['getCheckIfIsNew'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->configMock = $this->getMockBuilder(Config::class)
            ->setMethods(['getEventByAction', 'getTrackFieldModel', 'getEventModel', 'getActivityModuleConstant'])
            ->disableOriginalConstructor()
            ->getMock();

        $objectManager = new ObjectManager($this);

        $this->saveAfter = $objectManager->getObject(
            SaveAfter::class,
            [
                'processor' => $this->processorMock,
                'helper' => $this->helperMock,
            ]
        );
    }

    /**
     * @requires PHP 7.0
     */
    public function testExecute()
    {
        $this->helperMock->expects($this->once())
            ->method('isEnable')
            ->willReturn(true);

        $this->observerMock
            ->expects($this->atLeastOnce())
            ->method('getEvent')
            ->willReturn($this->eventMock);

        $this->eventMock
            ->expects($this->atLeastOnce())
            ->method('getObject')
            ->willReturn($this->objectMock);

        $this->objectMock
            ->expects($this->atLeastOnce())
            ->method('getCheckIfIsNew')
            ->willReturn(true);

        $this->processorMock->expects($this->once())
            ->method('modelAddAfter')
            ->with($this->objectMock)
            ->willReturnSelf();

        $this->assertTrue($this->saveAfter->execute($this->observerMock));
    }

    /**
     * @requires PHP 7.0
     */
    public function testExecuteIsEnableReturnFalse()
    {
        $this->helperMock->expects($this->once())
            ->method('isEnable')
            ->willReturn(false);

        $this->assertEquals($this->observerMock, $this->saveAfter->execute($this->observerMock));
    }

    /**
     * @requires PHP 7.0
     */
    public function testExecuteGetCheckIfIsNewReturnFalse()
    {
        $this->helperMock->expects($this->once())
            ->method('isEnable')
            ->willReturn(true);

        $this->observerMock
            ->expects($this->atLeastOnce())
            ->method('getEvent')
            ->willReturn($this->eventMock);
        $this->eventMock
            ->expects($this->atLeastOnce())
            ->method('getObject')
            ->willReturn($this->objectMock);

        $this->objectMock
            ->expects($this->atLeastOnce())
            ->method('getCheckIfIsNew')
            ->willReturn(false);

        $this->processorMock->expects($this->once())
            ->method('validate')
            ->with($this->objectMock)
            ->willReturn(true);

        $this->processorMock->expects($this->once())
            ->method('modelEditAfter')
            ->with($this->objectMock)
            ->willReturnSelf();

        $this->assertTrue($this->saveAfter->execute($this->observerMock));
    }
}

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
use MageOS\AdminActivityLog\Model\Processor;
use MageOS\AdminActivityLog\Observer\DeleteAfter;
use PHPUnit\Framework\TestCase;

class DeleteAfterTest extends TestCase
{
    public $deleteAfter;

    public $processorMock;

    public $helperMock;

    public $observerMock;

    public $eventMock;

    public $objectMock;

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
            ->setMethods([])
            ->disableOriginalConstructor()
            ->getMock();

        $objectManager = new ObjectManager($this);

        $this->deleteAfter = $objectManager->getObject(
            DeleteAfter::class,
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

        $this->processorMock->expects($this->once())
            ->method('modelDeleteAfter')
            ->with($this->objectMock)
            ->willReturnSelf();

        $this->assertNull($this->deleteAfter->execute($this->observerMock));
    }

    /**
     * @requires PHP 7.0
     */
    public function testExecuteIsEnableFalse()
    {
        $this->helperMock->expects($this->once())
            ->method('isEnable')
            ->willReturn(false);

        $this->assertEquals($this->observerMock, $this->deleteAfter->execute($this->observerMock));
    }
}

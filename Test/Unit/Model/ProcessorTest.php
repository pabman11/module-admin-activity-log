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

namespace MageOS\AdminActivityLog\Test\Unit\Model;

use Magento\Backend\Model\Auth\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Event;
use Magento\Framework\Event\Observer;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Store\Model\StoreManagerInterface;
use MageOS\AdminActivityLog\Api\ActivityRepositoryInterface;
use MageOS\AdminActivityLog\Helper\Data;
use MageOS\AdminActivityLog\Model\Activity\Status;
use MageOS\AdminActivityLog\Model\ActivityFactory;
use MageOS\AdminActivityLog\Model\ActivityLogDetailFactory;
use MageOS\AdminActivityLog\Model\Config;
use MageOS\AdminActivityLog\Model\Handler;
use MageOS\AdminActivityLog\Model\Processor;
use PHPUnit\Framework\TestCase;

/**
 * Class ProcessorTest
 * @package MageOS\AdminActivityLog\Test\Unit\Model
 */
class ProcessorTest extends TestCase
{
    public $configMock;

    public $actionName = '';

    public $fullActionName = 'catalog_product_save';

    public $observerMock;

    public $handlerMock;

    public $getEvent;

    public $lastAction = '';

    public $moduleName = 'module_name';

    public $eventConfig = ['action' => 'save', 'module' => 'catalog_products'];

    /**
     * @requires PHP 7.0
     */
    public function setUp()
    {
        $this->configMock = $this->getMockBuilder(Config::class)
            ->setMethods(['getEventByAction', 'getTrackFieldModel', 'getEventModel', 'getActivityModuleConstant'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->sessionMock = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->handlerMock = $this->getMockBuilder(Handler::class)
            ->setMethods(['request'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->observerMock = $this->getMockBuilder(Observer::class)
            ->setMethods(['getId', 'getEvent'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->eventMock = $this
            ->getMockBuilder(Event::class)
            ->setMethods(['getObject'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectMock = $this
            ->getMockBuilder(DataObject::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->remoteAddressMock = $this->getMockBuilder(RemoteAddress::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->activityFactoryMock = $this->getMockBuilder(ActivityFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->activityLogDetailFactoryMock = $this->getMockBuilder(
            ActivityLogDetailFactory::class
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->storeManagerInterfaceMock = $this->getMockBuilder(StoreManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->dateTimeMock = $this->getMockBuilder(DateTime::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->activityRepositoryInterfaceMock = $this->getMockBuilder(
            ActivityRepositoryInterface::class
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->dataMock = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->managerInterfaceMock = $this->getMockBuilder(ManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestInterfaceMock = $this->getMockBuilder(RequestInterface::class)
            ->setMethods([
                'getModuleName',
                'getControllerName',
                'setModuleName',
                'getActionName',
                'setActionName',
                'getParam',
                'getParams',
                'getCookie',
                'setParams',
                'isSecure'
            ])
            ->disableOriginalConstructor()
            ->getMock();

        $this->statusMock = $this->getMockBuilder(Status::class)
            ->disableOriginalConstructor()
            ->getMock();

        $objectManager = new ObjectManager($this);

        $this->processorTest = $objectManager->getObject(
            Processor::class,
            [
                'config' => $this->configMock,
                'authSession' => $this->sessionMock,
                'handler' => $this->handlerMock,
                'remoteAddress' => $this->remoteAddressMock,
                'activityFactory' => $this->activityFactoryMock,
                'activityDetailFactory' => $this->activityLogDetailFactoryMock,
                'storeManager' => $this->storeManagerInterfaceMock,
                'dateTime' => $this->dateTimeMock,
                'activityRepository' => $this->activityRepositoryInterfaceMock,
                'helper' => $this->dataMock,
                'messageManager' => $this->managerInterfaceMock,
                'request' => $this->requestInterfaceMock,
                'status' => $this->statusMock
            ]
        );
    }

    public function testValidate()
    {
        $this->observerMock
            ->expects($this->any())
            ->method('getEvent')
            ->willReturnSelf();

        $this->dataMock
            ->expects($this->any())
            ->method('isWildCardModel')
            ->with($this->observerMock)
            ->willReturn(false);

        $this->configMock
            ->expects($this->any())
            ->method('getEventByAction')
            ->with('catalog_product_save')
            ->willReturn($this->eventConfig);
    }
}

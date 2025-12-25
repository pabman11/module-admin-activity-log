<?php
/**
 * MageOS
 *
 * @category   MageOS
 * @package    MageOS_AdminActivityLog
 * @copyright  Copyright (C) 2018 Kiwi Commerce Ltd (https://kiwicommerce.co.uk/)
 * @copyright  Copyright (C) 2024 MageOS (https://mage-os.org/)
 * @license    https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

declare(strict_types=1);

namespace MageOS\AdminActivityLog\Test\Unit\Model;

use Magento\Backend\Model\Auth\Session;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Store\Model\StoreManagerInterface;
use MageOS\AdminActivityLog\Api\ActivityRepositoryInterface;
use MageOS\AdminActivityLog\Helper\Data as Helper;
use MageOS\AdminActivityLog\Model\Activity\Status;
use MageOS\AdminActivityLog\Model\Activity\SystemConfig;
use MageOS\AdminActivityLog\Model\ActivityFactory;
use MageOS\AdminActivityLog\Model\ActivityLogDetailFactory;
use MageOS\AdminActivityLog\Model\Config;
use MageOS\AdminActivityLog\Model\Handler;
use MageOS\AdminActivityLog\Model\Handler\PostDispatch;
use MageOS\AdminActivityLog\Model\Processor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ProcessorTest extends TestCase
{
    private Config&MockObject $config;
    private Session&MockObject $authSession;
    private Handler&MockObject $handler;
    private RemoteAddress&MockObject $remoteAddress;
    private ActivityFactory&MockObject $activityFactory;
    private ActivityLogDetailFactory&MockObject $activityDetailFactory;
    private StoreManagerInterface&MockObject $storeManager;
    private DateTime&MockObject $dateTime;
    private ActivityRepositoryInterface&MockObject $activityRepository;
    private Helper&MockObject $helper;
    private ManagerInterface&MockObject $messageManager;
    private RequestInterface&MockObject $request;
    private Http&MockObject $httpRequest;
    private Status&MockObject $status;
    private PostDispatch&MockObject $postDispatch;
    private SystemConfig&MockObject $systemConfig;
    private LoggerInterface&MockObject $logger;
    private ResourceConnection&MockObject $resourceConnection;
    private Processor $processor;

    protected function setUp(): void
    {
        $this->config = $this->createMock(Config::class);
        $this->authSession = $this->createMock(Session::class);
        $this->handler = $this->createMock(Handler::class);
        $this->remoteAddress = $this->createMock(RemoteAddress::class);
        $this->activityFactory = $this->createMock(ActivityFactory::class);
        $this->activityDetailFactory = $this->createMock(ActivityLogDetailFactory::class);
        $this->storeManager = $this->createMock(StoreManagerInterface::class);
        $this->dateTime = $this->createMock(DateTime::class);
        $this->activityRepository = $this->createMock(ActivityRepositoryInterface::class);
        $this->helper = $this->createMock(Helper::class);
        $this->messageManager = $this->createMock(ManagerInterface::class);
        $this->request = $this->createMock(RequestInterface::class);
        $this->httpRequest = $this->createMock(Http::class);
        $this->status = $this->createMock(Status::class);
        $this->postDispatch = $this->createMock(PostDispatch::class);
        $this->systemConfig = $this->createMock(SystemConfig::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->resourceConnection = $this->createMock(ResourceConnection::class);

        $this->processor = new Processor(
            $this->config,
            $this->authSession,
            $this->handler,
            $this->remoteAddress,
            $this->activityFactory,
            $this->activityDetailFactory,
            $this->storeManager,
            $this->dateTime,
            $this->activityRepository,
            $this->helper,
            $this->messageManager,
            $this->request,
            $this->httpRequest,
            $this->status,
            $this->postDispatch,
            $this->systemConfig,
            $this->logger,
            $this->resourceConnection
        );
    }

    /**
     * @dataProvider escapeStringDataProvider
     */
    public function testEscapeString(string $input, string $delimiter, string $expected): void
    {
        $result = $this->processor->escapeString($input, $delimiter);

        $this->assertSame($expected, $result);
    }

    /**
     * @return array<string, array{input: string, delimiter: string, expected: string}>
     */
    public static function escapeStringDataProvider(): array
    {
        return [
            'underscore with space delimiter' => [
                'input' => 'catalog_product_view',
                'delimiter' => ' ',
                'expected' => 'Catalog Product View'
            ],
            'underscore with slash delimiter' => [
                'input' => 'catalog_product_view',
                'delimiter' => '/',
                'expected' => 'Catalog/Product/View'
            ],
            'single word' => [
                'input' => 'products',
                'delimiter' => ' ',
                'expected' => 'Products'
            ],
            'uppercase input' => [
                'input' => 'CATALOG_PRODUCT',
                'delimiter' => ' ',
                'expected' => 'Catalog Product'
            ],
            'mixed case input' => [
                'input' => 'CaTaLoG_ProDuCt',
                'delimiter' => ' ',
                'expected' => 'Catalog Product'
            ],
            'empty string' => [
                'input' => '',
                'delimiter' => ' ',
                'expected' => ''
            ],
            'multiple underscores' => [
                'input' => 'admin__activity__log',
                'delimiter' => ' ',
                'expected' => 'Admin Activity Log'
            ],
            'trailing underscore' => [
                'input' => 'catalog_product_',
                'delimiter' => ' ',
                'expected' => 'Catalog Product'
            ],
            'leading underscore' => [
                'input' => '_catalog_product',
                'delimiter' => ' ',
                'expected' => 'Catalog Product'
            ],
        ];
    }

    /**
     * @dataProvider isValidActionDataProvider
     */
    public function testIsValidAction(string $module, string $fullAction, bool $expected): void
    {
        $result = $this->processor->isValidAction($module, $fullAction);

        $this->assertSame($expected, $result);
    }

    /**
     * @return array<string, array{module: string, fullAction: string, expected: bool}>
     */
    public static function isValidActionDataProvider(): array
    {
        return [
            'valid action' => [
                'module' => 'catalog',
                'fullAction' => 'catalog_product_edit',
                'expected' => true
            ],
            'skip action - mui_index_render' => [
                'module' => 'mui',
                'fullAction' => 'mui_index_render',
                'expected' => false
            ],
            'skip action - adminactivity_activity_index' => [
                'module' => 'adminactivity',
                'fullAction' => 'adminactivity_activity_index',
                'expected' => false
            ],
            'skip action - adminactivity_activity_log' => [
                'module' => 'adminactivity',
                'fullAction' => 'adminactivity_activity_log',
                'expected' => false
            ],
            'skip action - adminactivity_activity_revert' => [
                'module' => 'adminactivity',
                'fullAction' => 'adminactivity_activity_revert',
                'expected' => false
            ],
            'skip module - mui' => [
                'module' => 'mui',
                'fullAction' => 'some_valid_action',
                'expected' => false
            ],
            'skip module - MUI uppercase' => [
                'module' => 'MUI',
                'fullAction' => 'some_valid_action',
                'expected' => false
            ],
            'skip action case insensitive' => [
                'module' => 'adminactivity',
                'fullAction' => 'ADMINACTIVITY_ACTIVITY_INDEX',
                'expected' => false
            ],
            'valid customer action' => [
                'module' => 'customer',
                'fullAction' => 'customer_index_edit',
                'expected' => true
            ],
            'valid sales action' => [
                'module' => 'sales',
                'fullAction' => 'sales_order_view',
                'expected' => true
            ],
        ];
    }

    public function testGetScopeReturnsStoresWhenStoreParamIsOne(): void
    {
        $this->request
            ->method('getParam')
            ->willReturnMap([
                ['store', null, 1],
                ['website', null, null],
                ['scope', null, null]
            ]);

        $result = $this->processor->getScope();

        $this->assertSame('stores', $result);
    }

    public function testGetScopeReturnsStoresWhenScopeIsStores(): void
    {
        $this->request
            ->method('getParam')
            ->willReturnMap([
                ['store', null, 0],
                ['website', null, null],
                ['scope', null, 'stores']
            ]);

        $result = $this->processor->getScope();

        $this->assertSame('stores', $result);
    }

    public function testGetScopeReturnsWebsiteWhenWebsiteParamIsOne(): void
    {
        $this->request
            ->method('getParam')
            ->willReturnMap([
                ['store', null, 0],
                ['website', null, 1],
                ['scope', null, null]
            ]);

        $result = $this->processor->getScope();

        $this->assertSame('website', $result);
    }

    public function testGetScopeReturnsDefaultWhenNoParams(): void
    {
        $this->request
            ->method('getParam')
            ->willReturnMap([
                ['store', null, null],
                ['website', null, null],
                ['scope', null, null]
            ]);

        $result = $this->processor->getScope();

        $this->assertSame('default', $result);
    }

    public function testGetConfigReturnsConfigInstance(): void
    {
        $result = $this->processor->getConfig();

        $this->assertSame($this->config, $result);
    }

    public function testGetHandlerReturnsHandlerInstance(): void
    {
        $result = $this->processor->getHandler();

        $this->assertSame($this->handler, $result);
    }

    public function testGetRequestReturnsRequestInstance(): void
    {
        $result = $this->processor->getRequest();

        $this->assertSame($this->request, $result);
    }

    public function testGetRemoteAddressReturnsRemoteAddressInstance(): void
    {
        $result = $this->processor->getRemoteAddress();

        $this->assertSame($this->remoteAddress, $result);
    }

    public function testGetInitActionReturnsEmptyStringInitially(): void
    {
        $result = $this->processor->getInitAction();

        $this->assertSame('', $result);
    }

    public function testInitSetsActionNameAndEventConfig(): void
    {
        $fullActionName = 'catalog_product_edit';
        $actionName = 'edit';
        $eventConfig = ['module' => 'catalog_product', 'action' => 'edit'];

        $this->config
            ->expects($this->once())
            ->method('getEventByAction')
            ->with($fullActionName)
            ->willReturn($eventConfig);

        $result = $this->processor->init($fullActionName, $actionName);

        $this->assertSame($this->processor, $result);
        $this->assertSame($fullActionName, $this->processor->getInitAction());
    }

    public function testGetEventConfigReturnsNullInitially(): void
    {
        $result = $this->processor->getEventConfig();

        $this->assertNull($result);
    }

    public function testGetEventConfigWithTypeReturnsSpecificValue(): void
    {
        $fullActionName = 'catalog_product_edit';
        $actionName = 'edit';
        $eventConfig = ['module' => 'catalog_product', 'action' => 'edit'];

        $this->config
            ->method('getEventByAction')
            ->with($fullActionName)
            ->willReturn($eventConfig);

        $this->processor->init($fullActionName, $actionName);

        $this->assertSame('catalog_product', $this->processor->getEventConfig('module'));
        $this->assertSame('edit', $this->processor->getEventConfig('action'));
        $this->assertNull($this->processor->getEventConfig('nonexistent'));
    }
}

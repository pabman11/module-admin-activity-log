<?php
/**
 * MageOS
 *
 * @category   MageOS
 * @package    MageOS_AdminActivityLog
 * @copyright  Copyright (C) 2018 Kiwi Commerce Ltd (https://kiwicommerce.co.uk/)
 * @copyright  Copyright (C) 2025 MageOS (https://mage-os.org/)
 * @license    https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

declare(strict_types=1);

namespace MageOS\AdminActivityLog\Test\Unit\Model;

use MageOS\AdminActivityLog\Api\FieldCheckerInterface;
use MageOS\AdminActivityLog\Api\ModelResolverInterface;
use MageOS\AdminActivityLog\Helper\Data;
use MageOS\AdminActivityLog\Model\Activity;
use MageOS\AdminActivityLog\Model\Activity\SystemConfig;
use MageOS\AdminActivityLog\Model\Activity\ThemeConfig;
use MageOS\AdminActivityLog\Model\ActivityFactory;
use MageOS\AdminActivityLog\Model\ActivityLogDetailFactory;
use MageOS\AdminActivityLog\Model\ActivityLogFactory;
use MageOS\AdminActivityLog\Model\ActivityRepository;
use MageOS\AdminActivityLog\Model\ResourceModel\Activity\Collection as ActivityCollection;
use MageOS\AdminActivityLog\Model\ResourceModel\Activity\CollectionFactory as ActivityCollectionFactory;
use MageOS\AdminActivityLog\Model\ResourceModel\ActivityLog\Collection as ActivityLogCollection;
use MageOS\AdminActivityLog\Model\ResourceModel\ActivityLog\CollectionFactory as LogCollectionFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ActivityRepositoryTest extends TestCase
{
    private ActivityFactory&MockObject $activityFactory;
    private ActivityCollectionFactory&MockObject $collectionFactory;
    private ActivityLogDetailFactory&MockObject $activityLogDetailFactory;
    private ActivityLogFactory&MockObject $activityLogFactory;
    private LogCollectionFactory&MockObject $logCollectionFactory;
    private SystemConfig&MockObject $systemConfig;
    private ThemeConfig&MockObject $themeConfig;
    private ModelResolverInterface&MockObject $modelResolver;
    private FieldCheckerInterface&MockObject $protectedFieldChecker;
    private Data&MockObject $dataHelper;
    private ActivityRepository $repository;

    protected function setUp(): void
    {
        $this->activityFactory = $this->createMock(ActivityFactory::class);
        $this->collectionFactory = $this->createMock(ActivityCollectionFactory::class);
        $this->activityLogDetailFactory = $this->createMock(ActivityLogDetailFactory::class);
        $this->activityLogFactory = $this->createMock(ActivityLogFactory::class);
        $this->logCollectionFactory = $this->createMock(LogCollectionFactory::class);
        $this->systemConfig = $this->createMock(SystemConfig::class);
        $this->themeConfig = $this->createMock(ThemeConfig::class);
        $this->modelResolver = $this->createMock(ModelResolverInterface::class);
        $this->protectedFieldChecker = $this->createMock(FieldCheckerInterface::class);
        $this->dataHelper = $this->createMock(Data::class);

        $this->repository = new ActivityRepository(
            $this->activityFactory,
            $this->collectionFactory,
            $this->activityLogDetailFactory,
            $this->activityLogFactory,
            $this->logCollectionFactory,
            $this->systemConfig,
            $this->themeConfig,
            $this->modelResolver,
            $this->protectedFieldChecker,
            $this->dataHelper
        );
    }

    /**
     * @dataProvider getMethodNameDataProvider
     */
    public function testGetMethodName(string $field, string $expected): void
    {
        $result = $this->repository->getMethodName($field);

        $this->assertSame($expected, $result);
    }

    /**
     * @return array<string, array{field: string, expected: string}>
     */
    public static function getMethodNameDataProvider(): array
    {
        return [
            'simple field' => [
                'field' => 'name',
                'expected' => 'Name'
            ],
            'underscore field' => [
                'field' => 'first_name',
                'expected' => 'FirstName'
            ],
            'multiple underscores' => [
                'field' => 'customer_group_id',
                'expected' => 'CustomerGroupId'
            ],
            'uppercase field' => [
                'field' => 'NAME',
                'expected' => 'Name'
            ],
            'mixed case field' => [
                'field' => 'FiRsT_nAmE',
                'expected' => 'FirstName'
            ],
            'single letter segments' => [
                'field' => 'a_b_c',
                'expected' => 'ABC'
            ],
            'trailing underscore' => [
                'field' => 'field_name_',
                'expected' => 'FieldName'
            ],
        ];
    }

    public function testGetListReturnsCollection(): void
    {
        $mockCollection = $this->createMock(ActivityCollection::class);

        $this->collectionFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn($mockCollection);

        $result = $this->repository->getList();

        $this->assertSame($mockCollection, $result);
    }

    public function testGetListBeforeDateReturnsFilteredCollection(): void
    {
        $endDate = '2024-12-31 23:59:59';
        $mockCollection = $this->createMock(ActivityCollection::class);

        $this->collectionFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn($mockCollection);

        $mockCollection
            ->expects($this->once())
            ->method('addFieldToSelect')
            ->with('entity_id')
            ->willReturnSelf();

        $mockCollection
            ->expects($this->once())
            ->method('addFieldToFilter')
            ->with('created_at', ['lteq' => $endDate])
            ->willReturnSelf();

        $result = $this->repository->getListBeforeDate($endDate);

        $this->assertSame($mockCollection, $result);
    }

    public function testDeleteActivityByIdLoadsAndDeletesModel(): void
    {
        $activityId = 123;
        $mockActivity = $this->createMock(Activity::class);

        $this->activityFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn($mockActivity);

        $mockActivity
            ->expects($this->once())
            ->method('load')
            ->with($activityId)
            ->willReturnSelf();

        $mockActivity
            ->expects($this->once())
            ->method('delete');

        $this->repository->deleteActivityById($activityId);
    }

    public function testGetActivityLogReturnsFilteredCollection(): void
    {
        $activityId = 456;
        $mockCollection = $this->createMock(ActivityLogCollection::class);

        $this->logCollectionFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn($mockCollection);

        $mockCollection
            ->expects($this->once())
            ->method('addFieldToFilter')
            ->with('activity_id', ['eq' => $activityId])
            ->willReturnSelf();

        $result = $this->repository->getActivityLog($activityId);

        $this->assertSame($mockCollection, $result);
    }

    public function testGetActivityByIdReturnsLoadedActivity(): void
    {
        $activityId = 789;
        $mockActivity = $this->createMock(Activity::class);

        $this->activityFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn($mockActivity);

        $mockActivity
            ->expects($this->once())
            ->method('load')
            ->with($activityId)
            ->willReturnSelf();

        $result = $this->repository->getActivityById($activityId);

        $this->assertSame($mockActivity, $result);
    }
}

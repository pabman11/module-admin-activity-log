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

use InvalidArgumentException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\ObjectManagerInterface;
use MageOS\AdminActivityLog\Model\Activity;
use MageOS\AdminActivityLog\Model\ModelResolver;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class ModelResolverTest extends TestCase
{
    private ObjectManagerInterface&MockObject $objectManager;
    private ModelResolver $modelResolver;
    private ModelResolver $modelResolverWithAllowlist;

    protected function setUp(): void
    {
        $this->objectManager = $this->createMock(ObjectManagerInterface::class);
        $this->modelResolver = new ModelResolver($this->objectManager);
        $this->modelResolverWithAllowlist = new ModelResolver(
            $this->objectManager,
            [Activity::class]
        );
    }

    public function testGetModelWithValidClass(): void
    {
        // Use Activity class which is a concrete subclass of AbstractModel
        $className = Activity::class;
        $mockModel = $this->createMock(Activity::class);

        $this->objectManager
            ->expects($this->once())
            ->method('create')
            ->with($className)
            ->willReturn($mockModel);

        $result = $this->modelResolverWithAllowlist->getModel($className);

        $this->assertSame($mockModel, $result);
    }

    public function testGetModelWithInvalidClassThrowsException(): void
    {
        $className = stdClass::class;

        $this->objectManager->expects($this->never())->method('create');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Class "%s" is not in the allowed model classes list', $className));

        $this->modelResolver->getModel($className);
    }

    public function testGetModelWithNonExistentClassThrowsException(): void
    {
        $className = 'NonExistentClass\\That\\Does\\Not\\Exist';

        $this->objectManager->expects($this->never())->method('create');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Class "%s" is not in the allowed model classes list', $className));

        $this->modelResolver->getModel($className);
    }

    public function testLoadModelWithoutField(): void
    {
        $className = Activity::class;
        $entityId = 123;

        $mockModel = $this->createMock(Activity::class);

        $this->objectManager
            ->expects($this->once())
            ->method('create')
            ->with($className)
            ->willReturn($mockModel);

        $mockModel
            ->expects($this->once())
            ->method('load')
            ->with($entityId)
            ->willReturnSelf();

        $result = $this->modelResolverWithAllowlist->loadModel($className, $entityId);

        $this->assertSame($mockModel, $result);
    }

    public function testLoadModelWithField(): void
    {
        $className = Activity::class;
        $entityId = 'test-sku';
        $field = 'sku';

        $mockModel = $this->createMock(Activity::class);

        $this->objectManager
            ->expects($this->once())
            ->method('create')
            ->with($className)
            ->willReturn($mockModel);

        $mockModel
            ->expects($this->once())
            ->method('load')
            ->with($entityId, $field)
            ->willReturnSelf();

        $result = $this->modelResolverWithAllowlist->loadModel($className, $entityId, $field);

        $this->assertSame($mockModel, $result);
    }

    public function testLoadModelWithIntegerId(): void
    {
        $className = Activity::class;
        $entityId = 42;

        $mockModel = $this->createMock(Activity::class);

        $this->objectManager
            ->expects($this->once())
            ->method('create')
            ->with($className)
            ->willReturn($mockModel);

        $mockModel
            ->expects($this->once())
            ->method('load')
            ->with($entityId)
            ->willReturnSelf();

        $result = $this->modelResolverWithAllowlist->loadModel($className, $entityId);

        $this->assertSame($mockModel, $result);
    }

    public function testIsValidModelClassReturnsTrueForConcreteModel(): void
    {
        // Activity extends AbstractModel, so it should be valid
        $result = $this->modelResolver->isValidModelClass(Activity::class);

        $this->assertTrue($result);
    }

    public function testIsValidModelClassReturnsFalseForAbstractModelItself(): void
    {
        // AbstractModel itself is not a subclass of AbstractModel
        $result = $this->modelResolver->isValidModelClass(AbstractModel::class);

        $this->assertFalse($result);
    }

    public function testIsValidModelClassReturnsFalseForNonExistentClass(): void
    {
        $result = $this->modelResolver->isValidModelClass('NonExistent\\Class\\Name');

        $this->assertFalse($result);
    }

    public function testIsValidModelClassReturnsFalseForNonModelClass(): void
    {
        $result = $this->modelResolver->isValidModelClass(stdClass::class);

        $this->assertFalse($result);
    }

    public function testIsValidModelClassReturnsFalseForInterfaceClass(): void
    {
        $result = $this->modelResolver->isValidModelClass(ObjectManagerInterface::class);

        $this->assertFalse($result);
    }

    public function testIsAllowedModelClassReturnsFalseWhenNoAllowlistConfigured(): void
    {
        // Without allowlist, all classes should be disallowed
        $result = $this->modelResolver->isAllowedModelClass(Activity::class);

        $this->assertFalse($result);
    }

    public function testIsAllowedModelClassReturnsTrueForConfiguredClass(): void
    {
        $result = $this->modelResolverWithAllowlist->isAllowedModelClass(Activity::class);

        $this->assertTrue($result);
    }

    public function testIsAllowedModelClassReturnsFalseForUnconfiguredClass(): void
    {
        // stdClass is not in the allowlist
        $result = $this->modelResolverWithAllowlist->isAllowedModelClass(stdClass::class);

        $this->assertFalse($result);
    }

    public function testGetModelWithAllowedClassSucceeds(): void
    {
        $className = Activity::class;
        $mockModel = $this->createMock(Activity::class);

        $this->objectManager
            ->expects($this->once())
            ->method('create')
            ->with($className)
            ->willReturn($mockModel);

        $result = $this->modelResolverWithAllowlist->getModel($className);

        $this->assertSame($mockModel, $result);
    }

    public function testGetModelWithDisallowedClassThrowsException(): void
    {
        // Create a resolver with a very limited allowlist that doesn't include Activity
        $restrictedResolver = new ModelResolver(
            $this->objectManager,
            ['Some\\Other\\Model']
        );

        $this->objectManager->expects($this->never())->method('create');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Class "%s" is not in the allowed model classes list', Activity::class));

        $restrictedResolver->getModel(Activity::class);
    }
}

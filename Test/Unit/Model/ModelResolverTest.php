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

    protected function setUp(): void
    {
        $this->objectManager = $this->createMock(ObjectManagerInterface::class);
        $this->modelResolver = new ModelResolver($this->objectManager);
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

        $result = $this->modelResolver->getModel($className);

        $this->assertSame($mockModel, $result);
    }

    public function testGetModelWithInvalidClassThrowsException(): void
    {
        $className = stdClass::class;

        $this->objectManager->expects($this->never())->method('create');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Class "%s" is not a valid AbstractModel', $className));

        $this->modelResolver->getModel($className);
    }

    public function testGetModelWithNonExistentClassThrowsException(): void
    {
        $className = 'NonExistentClass\\That\\Does\\Not\\Exist';

        $this->objectManager->expects($this->never())->method('create');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Class "%s" is not a valid AbstractModel', $className));

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

        $result = $this->modelResolver->loadModel($className, $entityId);

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

        $result = $this->modelResolver->loadModel($className, $entityId, $field);

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

        $result = $this->modelResolver->loadModel($className, $entityId);

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
}

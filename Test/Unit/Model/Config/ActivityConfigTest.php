<?php
/**
 * MageOS
 *
 * @category   MageOS
 * @package    MageOS_AdminActivityLog
 * @copyright  Copyright (C) 2025 MageOS (https://mage-os.org/)
 * @license    https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

declare(strict_types=1);

namespace MageOS\AdminActivityLog\Test\Unit\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\DataObject;
use MageOS\AdminActivityLog\Model\Config;
use MageOS\AdminActivityLog\Model\Config\ActivityConfig;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Test class that simulates Magento's Interceptor pattern
 * Interceptors are generated classes that extend the original class
 */
class ValueInterceptor extends Value
{
}

/**
 * Another test subclass for inheritance testing
 */
class CustomConfigValue extends Value
{
}

class ActivityConfigTest extends TestCase
{
    private ScopeConfigInterface&MockObject $scopeConfig;
    private Config&MockObject $config;
    private ActivityConfig $activityConfig;

    protected function setUp(): void
    {
        $this->scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $this->config = $this->createMock(Config::class);

        $this->activityConfig = new ActivityConfig(
            $this->scopeConfig,
            $this->config
        );
    }

    /**
     * @dataProvider isWildCardModelDataProvider
     */
    public function testIsWildCardModel(DataObject|string $model, bool $expected): void
    {
        $result = $this->activityConfig->isWildCardModel($model);

        $this->assertSame($expected, $result);
    }

    /**
     * @return array<string, array{model: DataObject|string, expected: bool}>
     */
    public static function isWildCardModelDataProvider(): array
    {
        return [
            'exact class match with string' => [
                'model' => Value::class,
                'expected' => true,
            ],
            'subclass match simulating Interceptor with string' => [
                'model' => ValueInterceptor::class,
                'expected' => true,
            ],
            'custom subclass match with string' => [
                'model' => CustomConfigValue::class,
                'expected' => true,
            ],
            'non-wildcard class returns false' => [
                'model' => stdClass::class,
                'expected' => false,
            ],
            'unrelated DataObject returns false' => [
                'model' => DataObject::class,
                'expected' => false,
            ],
        ];
    }

    public function testIsWildCardModelWithValueInstance(): void
    {
        $model = $this->createMock(Value::class);

        $result = $this->activityConfig->isWildCardModel($model);

        $this->assertTrue($result);
    }

    public function testIsWildCardModelWithInterceptorInstance(): void
    {
        // Create a mock that simulates an Interceptor (subclass of Value)
        $model = $this->getMockBuilder(ValueInterceptor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $result = $this->activityConfig->isWildCardModel($model);

        $this->assertTrue($result);
    }

    public function testIsWildCardModelWithNonWildcardInstance(): void
    {
        $model = new DataObject();

        $result = $this->activityConfig->isWildCardModel($model);

        $this->assertFalse($result);
    }

    public function testIsWildCardModelWithAdditionalWildcardModels(): void
    {
        $activityConfig = new ActivityConfig(
            $this->scopeConfig,
            $this->config,
            [DataObject::class]
        );

        // DataObject should now be a wildcard model
        $this->assertTrue($activityConfig->isWildCardModel(DataObject::class));
        $this->assertTrue($activityConfig->isWildCardModel(new DataObject()));

        // Original Value should still be a wildcard model
        $this->assertTrue($activityConfig->isWildCardModel(Value::class));
    }

    public function testIsWildCardModelInheritanceChain(): void
    {
        // Test that deeply nested subclasses are still recognized
        // ValueInterceptor extends Value, which is in the wildcard list
        $result = $this->activityConfig->isWildCardModel(ValueInterceptor::class);

        $this->assertTrue($result);
    }
}

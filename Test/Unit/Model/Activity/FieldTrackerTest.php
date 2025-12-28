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

namespace MageOS\AdminActivityLog\Test\Unit\Model\Activity;

use Magento\Framework\DataObject;
use MageOS\AdminActivityLog\Api\ActivityConfigInterface;
use MageOS\AdminActivityLog\Model\Activity\FieldTracker;
use MageOS\AdminActivityLog\Model\Activity\SystemConfig;
use MageOS\AdminActivityLog\Model\Activity\ThemeConfig;
use MageOS\AdminActivityLog\Model\Config;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class FieldTrackerTest extends TestCase
{
    private SystemConfig&MockObject $systemConfig;
    private ThemeConfig&MockObject $themeConfig;
    private Config&MockObject $config;
    private ActivityConfigInterface&MockObject $activityConfig;
    private FieldTracker $fieldTracker;

    protected function setUp(): void
    {
        $this->systemConfig = $this->createMock(SystemConfig::class);
        $this->themeConfig = $this->createMock(ThemeConfig::class);
        $this->config = $this->createMock(Config::class);
        $this->activityConfig = $this->createMock(ActivityConfigInterface::class);

        $this->fieldTracker = new FieldTracker(
            $this->systemConfig,
            $this->themeConfig,
            $this->config,
            $this->activityConfig
        );
    }

    public function testGetFieldsWithArrayReturnsArray(): void
    {
        $skipFields = ['field1', 'field2', 'field3'];

        $result = $this->fieldTracker->getFields($skipFields);

        $this->assertSame($skipFields, $result);
    }

    public function testGetFieldsWithEmptyArrayReturnsEmptyArray(): void
    {
        $result = $this->fieldTracker->getFields([]);

        $this->assertSame([], $result);
    }

    public function testGetFieldsWithInvalidMethodNameReturnsEmptyArray(): void
    {
        $result = $this->fieldTracker->getFields('nonExistentMethod');

        $this->assertSame([], $result);
    }

    public function testGetFieldsWithEmptyStringReturnsEmptyArray(): void
    {
        $result = $this->fieldTracker->getFields('');

        $this->assertSame([], $result);
    }

    public function testGetSkipEditFieldDataReturnsConfigFields(): void
    {
        $expectedFields = ['created_at', 'updated_at', 'form_key'];

        $this->config
            ->expects($this->once())
            ->method('getGlobalSkipEditFields')
            ->willReturn($expectedFields);

        $result = $this->fieldTracker->getSkipEditFieldData();

        $this->assertSame($expectedFields, $result);
    }

    public function testValidateValueReturnsTrueWhenFieldInSkipFields(): void
    {
        $model = new DataObject(['form_key' => 'abc123']);
        $skipFields = ['form_key', 'created_at'];

        $result = $this->fieldTracker->validateValue($model, 'form_key', 'abc123', $skipFields);

        $this->assertTrue($result);
    }

    public function testValidateValueReturnsFalseWhenFieldNotInSkipFields(): void
    {
        $model = new DataObject(['name' => 'Test']);
        $skipFields = ['form_key', 'created_at'];

        $result = $this->fieldTracker->validateValue($model, 'name', 'Test', $skipFields);

        $this->assertFalse($result);
    }

    public function testValidateValueReturnsTrueWhenValueIsArray(): void
    {
        $model = new DataObject(['options' => ['a', 'b']]);

        $result = $this->fieldTracker->validateValue($model, 'options', ['a', 'b'], []);

        $this->assertTrue($result);
    }

    public function testValidateValueReturnsTrueWhenValueIsObject(): void
    {
        $model = new DataObject([]);

        $result = $this->fieldTracker->validateValue($model, 'complex', new stdClass(), []);

        $this->assertTrue($result);
    }

    public function testValidateValueReturnsTrueWhenOrigDataIsArray(): void
    {
        $model = new DataObject(['categories' => 'test']);
        $model->setOrigData(['categories' => ['cat1', 'cat2']]);

        $result = $this->fieldTracker->validateValue($model, 'categories', 'test', []);

        $this->assertTrue($result);
    }

    public function testValidateValueReturnsFalseForStringNotInSkipFields(): void
    {
        $model = new DataObject(['sku' => 'TEST-SKU']);
        $model->setOrigData(['sku' => 'OLD-SKU']);

        $result = $this->fieldTracker->validateValue($model, 'sku', 'TEST-SKU', []);

        $this->assertFalse($result);
    }

    public function testGetAddDataWithArraySkipFields(): void
    {
        $model = new DataObject([
            'name' => 'Test Product',
            'sku' => 'TEST-123',
            'form_key' => 'abc123',
            'price' => '99.99'
        ]);

        $skipFields = ['form_key'];

        $result = $this->fieldTracker->getAddData($model, $skipFields);

        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('sku', $result);
        $this->assertArrayHasKey('price', $result);
        $this->assertArrayNotHasKey('form_key', $result);

        $this->assertSame('', $result['name']['old_value']);
        $this->assertSame('Test Product', $result['name']['new_value']);
    }

    public function testGetAddDataWithEmptyModelReturnsEmptyArray(): void
    {
        $model = new DataObject([]);

        $result = $this->fieldTracker->getAddData($model, []);

        $this->assertSame([], $result);
    }

    public function testGetDeleteDataWithOrigData(): void
    {
        $model = new DataObject([]);
        $model->setOrigData([
            'name' => 'Test Product',
            'sku' => 'TEST-123',
            'form_key' => 'abc123'
        ]);

        $skipFields = ['form_key'];

        $result = $this->fieldTracker->getDeleteData($model, $skipFields);

        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('sku', $result);
        $this->assertArrayNotHasKey('form_key', $result);

        $this->assertSame('Test Product', $result['name']['old_value']);
        $this->assertSame('', $result['name']['new_value']);
    }
}

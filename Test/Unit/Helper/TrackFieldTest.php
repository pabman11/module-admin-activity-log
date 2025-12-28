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

namespace MageOS\AdminActivityLog\Test\Unit\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\DataObject;
use MageOS\AdminActivityLog\Api\ActivityConfigInterface;
use MageOS\AdminActivityLog\Helper\TrackField;
use MageOS\AdminActivityLog\Model\Activity\SystemConfig;
use MageOS\AdminActivityLog\Model\Activity\ThemeConfig;
use MageOS\AdminActivityLog\Model\Config;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class TrackFieldTest extends TestCase
{
    private Context&MockObject $context;
    private SystemConfig&MockObject $systemConfig;
    private ThemeConfig&MockObject $themeConfig;
    private Config&MockObject $config;
    private ActivityConfigInterface&MockObject $activityConfig;
    private TrackField $trackField;

    protected function setUp(): void
    {
        $this->context = $this->createMock(Context::class);
        $this->systemConfig = $this->createMock(SystemConfig::class);
        $this->themeConfig = $this->createMock(ThemeConfig::class);
        $this->config = $this->createMock(Config::class);
        $this->activityConfig = $this->createMock(ActivityConfigInterface::class);

        $this->trackField = new TrackField(
            $this->context,
            $this->systemConfig,
            $this->themeConfig,
            $this->config,
            $this->activityConfig
        );
    }

    public function testGetFieldsWithArrayReturnsArray(): void
    {
        $skipFields = ['field1', 'field2', 'field3'];

        $result = $this->trackField->getFields($skipFields);

        $this->assertSame($skipFields, $result);
    }

    public function testGetFieldsWithEmptyArrayReturnsEmptyArray(): void
    {
        $result = $this->trackField->getFields([]);

        $this->assertSame([], $result);
    }

    public function testGetFieldsWithMethodNameCallsMethod(): void
    {
        $result = $this->callDeprecatedMethod(fn() => $this->trackField->getFields('getProductFieldData'));

        $this->assertIsArray($result);
        $this->assertContains('form_key', $result);
        $this->assertContains('current_product_id', $result);
    }

    /**
     * Helper method to call deprecated methods without triggering test failure
     *
     * @param callable $callback The callback that calls the deprecated method
     * @return mixed The result of the callback
     */
    private function callDeprecatedMethod(callable $callback): mixed
    {
        $previousHandler = set_error_handler(function (int $errno, string $errstr) use (&$previousHandler): bool {
            if ($errno === E_USER_DEPRECATED) {
                return true; // Suppress deprecation warning
            }
            if ($previousHandler !== null) {
                return $previousHandler($errno, $errstr);
            }
            return false;
        });

        try {
            return $callback();
        } finally {
            restore_error_handler();
        }
    }

    public function testGetFieldsWithInvalidMethodNameReturnsEmptyArray(): void
    {
        $result = $this->trackField->getFields('nonExistentMethod');

        $this->assertSame([], $result);
    }

    public function testGetFieldsWithEmptyStringReturnsEmptyArray(): void
    {
        $result = $this->trackField->getFields('');

        $this->assertSame([], $result);
    }

    public function testGetSkipEditFieldDataReturnsConfigFields(): void
    {
        $expectedFields = ['created_at', 'updated_at', 'form_key'];

        $this->config
            ->expects($this->once())
            ->method('getGlobalSkipEditFields')
            ->willReturn($expectedFields);

        $result = $this->trackField->getSkipEditFieldData();

        $this->assertSame($expectedFields, $result);
    }

    public function testValidateValueReturnsTrueWhenFieldInSkipFields(): void
    {
        $model = new DataObject(['form_key' => 'abc123']);
        $skipFields = ['form_key', 'created_at'];

        $result = $this->trackField->validateValue($model, 'form_key', 'abc123', $skipFields);

        $this->assertTrue($result);
    }

    public function testValidateValueReturnsFalseWhenFieldNotInSkipFields(): void
    {
        $model = new DataObject(['name' => 'Test']);
        $skipFields = ['form_key', 'created_at'];

        $result = $this->trackField->validateValue($model, 'name', 'Test', $skipFields);

        $this->assertFalse($result);
    }

    public function testValidateValueReturnsTrueWhenValueIsArray(): void
    {
        $model = new DataObject(['options' => ['a', 'b']]);

        $result = $this->trackField->validateValue($model, 'options', ['a', 'b'], []);

        $this->assertTrue($result);
    }

    public function testValidateValueReturnsTrueWhenValueIsObject(): void
    {
        $model = new DataObject([]);

        $result = $this->trackField->validateValue($model, 'complex', new stdClass(), []);

        $this->assertTrue($result);
    }

    public function testValidateValueReturnsTrueWhenOrigDataIsArray(): void
    {
        $model = new DataObject(['categories' => 'test']);
        $model->setOrigData(['categories' => ['cat1', 'cat2']]);

        $result = $this->trackField->validateValue($model, 'categories', 'test', []);

        $this->assertTrue($result);
    }

    public function testValidateValueReturnsFalseForStringNotInSkipFields(): void
    {
        $model = new DataObject(['sku' => 'TEST-SKU']);
        $model->setOrigData(['sku' => 'OLD-SKU']);

        $result = $this->trackField->validateValue($model, 'sku', 'TEST-SKU', []);

        $this->assertFalse($result);
    }

    public function testGetProductFieldDataReturnsExpectedFields(): void
    {
        $result = $this->callDeprecatedMethod(fn() => $this->trackField->getProductFieldData());

        $this->assertIsArray($result);
        $this->assertContains('form_key', $result);
        $this->assertContains('current_product_id', $result);
        $this->assertContains('entity_id', $result);
        $this->assertContains('updated_at', $result);
    }

    public function testGetCategoryFieldDataReturnsExpectedFields(): void
    {
        $result = $this->callDeprecatedMethod(fn() => $this->trackField->getCategoryFieldData());

        $this->assertIsArray($result);
        $this->assertContains('form_key', $result);
        $this->assertContains('updated_at', $result);
        $this->assertContains('created_at', $result);
    }

    public function testGetCustomerFieldDataReturnsExpectedFields(): void
    {
        $result = $this->callDeprecatedMethod(fn() => $this->trackField->getCustomerFieldData());

        $this->assertIsArray($result);
        $this->assertContains('form_key', $result);
        $this->assertContains('entity_id', $result);
        $this->assertContains('rp_token', $result);
    }

    public function testGetSystemConfigFieldDataReturnsExpectedFields(): void
    {
        $result = $this->callDeprecatedMethod(fn() => $this->trackField->getSystemConfigFieldData());

        $this->assertIsArray($result);
        $this->assertContains('check_if_is_new', $result);
    }

    public function testGetThemeConfigFieldDataReturnsExpectedFields(): void
    {
        $result = $this->callDeprecatedMethod(fn() => $this->trackField->getThemeConfigFieldData());

        $this->assertIsArray($result);
        $this->assertContains('back', $result);
        $this->assertContains('scope', $result);
        $this->assertContains('scope_id', $result);
        $this->assertContains('form_key', $result);
        $this->assertContains('head_includes', $result);
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

        $result = $this->trackField->getAddData($model, $skipFields);

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

        $result = $this->trackField->getAddData($model, []);

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

        $result = $this->trackField->getDeleteData($model, $skipFields);

        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('sku', $result);
        $this->assertArrayNotHasKey('form_key', $result);

        $this->assertSame('Test Product', $result['name']['old_value']);
        $this->assertSame('', $result['name']['new_value']);
    }

    public function testGetAdminUserFieldDataReturnsExpectedFields(): void
    {
        $result = $this->callDeprecatedMethod(fn() => $this->trackField->getAdminUserFieldData());

        $this->assertIsArray($result);
        $this->assertContains('form_key', $result);
        $this->assertContains('password_confirmation', $result);
        $this->assertContains('current_password', $result);
        $this->assertContains('user_roles', $result);
    }

    public function testGetOrderFieldDataReturnsExpectedFields(): void
    {
        $result = $this->callDeprecatedMethod(fn() => $this->trackField->getOrderFieldData());

        $this->assertIsArray($result);
        $this->assertContains('check_if_is_new', $result);
        $this->assertContains('created_at', $result);
        $this->assertContains('updated_at', $result);
        $this->assertContains('protect_code', $result);
    }

    public function testGetIntegrationFieldDataReturnsExpectedFields(): void
    {
        $result = $this->callDeprecatedMethod(fn() => $this->trackField->getIntegrationFieldData());

        $this->assertIsArray($result);
        $this->assertContains('form_key', $result);
        $this->assertContains('current_password', $result);
        $this->assertContains('consumer_key', $result);
        $this->assertContains('consumer_secret', $result);
    }
}

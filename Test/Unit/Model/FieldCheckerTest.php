<?php

declare(strict_types=1);

/**
 * MageOS
 *
 * @category   MageOS
 * @package    MageOS_AdminActivityLog
 * @copyright  Copyright (C) 2025 MageOS (https://mage-os.org/)
 * @license    https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

namespace MageOS\AdminActivityLog\Test\Unit\Model;

use MageOS\AdminActivityLog\Model\FieldChecker;
use PHPUnit\Framework\TestCase;

class FieldCheckerTest extends TestCase
{
    private FieldChecker $checker;

    protected function setUp(): void
    {
        $this->checker = new FieldChecker([
            'password',
            'password_hash',
            'cc_number',
            'api_key',
            'secret_key',
        ]);
    }

    public function testGetProtectedFieldsReturnsConfiguredFields(): void
    {
        $result = $this->checker->getProtectedFields();

        $this->assertContains('password', $result);
        $this->assertContains('password_hash', $result);
        $this->assertContains('cc_number', $result);
        $this->assertContains('api_key', $result);
        $this->assertContains('secret_key', $result);
        $this->assertCount(5, $result);
    }

    /**
     * @dataProvider isFieldProtectedDataProvider
     */
    public function testIsFieldProtected(string $fieldName, bool $expected): void
    {
        $result = $this->checker->isFieldProtected($fieldName);

        $this->assertSame($expected, $result);
    }

    /**
     * @return array<string, array{fieldName: string, expected: bool}>
     */
    public static function isFieldProtectedDataProvider(): array
    {
        return [
            'password is protected' => [
                'fieldName' => 'password',
                'expected' => true
            ],
            'password_hash is protected' => [
                'fieldName' => 'password_hash',
                'expected' => true
            ],
            'cc_number is protected' => [
                'fieldName' => 'cc_number',
                'expected' => true
            ],
            'api_key is protected' => [
                'fieldName' => 'api_key',
                'expected' => true
            ],
            'secret_key is protected' => [
                'fieldName' => 'secret_key',
                'expected' => true
            ],
            'username is not protected' => [
                'fieldName' => 'username',
                'expected' => false
            ],
            'email is not protected' => [
                'fieldName' => 'email',
                'expected' => false
            ],
            'PASSWORD uppercase is not protected' => [
                'fieldName' => 'PASSWORD',
                'expected' => false
            ],
            'empty string is not protected' => [
                'fieldName' => '',
                'expected' => false
            ],
        ];
    }

    public function testEmptyProtectedFieldsArray(): void
    {
        $checker = new FieldChecker([]);

        $this->assertSame([], $checker->getProtectedFields());
        $this->assertFalse($checker->isFieldProtected('password'));
    }
}

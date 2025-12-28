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

namespace MageOS\AdminActivityLog\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\DataObject;
use MageOS\AdminActivityLog\Api\ActivityConfigInterface;
use MageOS\AdminActivityLog\Model\Config;

/**
 * Service class for admin activity configuration
 *
 * Provides configuration access for admin activity logging module.
 * This is the preferred way to access configuration over Helper\Data.
 */
class ActivityConfig implements ActivityConfigInterface
{
    /**
     * Configuration path constants
     */
    public const ACTIVITY_ENABLE = 'admin/admin_activity/general/enable';
    public const LOGIN_ACTIVITY_ENABLE = 'admin/admin_activity/general/login_activity';
    public const PAGE_VISIT_ENABLE = 'admin/admin_activity/general/page_visit';
    public const CLEAR_LOG_DAYS = 'admin/admin_activity/general/clearlog';
    public const MODULE_ORDER = 'admin/admin_activity/module/order';
    public const MODULE_PRODUCT = 'admin/admin_activity/module/product';
    public const MODULE_CATEGORY = 'admin/admin_activity/module/category';
    public const MODULE_CUSTOMER = 'admin/admin_activity/module/customer';
    public const MODULE_PROMOTION = 'admin/admin_activity/module/promotion';
    public const MODULE_EMAIL = 'admin/admin_activity/module/email';
    public const MODULE_PAGE = 'admin/admin_activity/module/page';
    public const MODULE_BLOCK = 'admin/admin_activity/module/block';
    public const MODULE_WIDGET = 'admin/admin_activity/module/widget';
    public const MODULE_THEME = 'admin/admin_activity/module/theme';
    public const MODULE_SYSTEM_CONFIG = 'admin/admin_activity/module/system_config';
    public const MODULE_ATTRIBUTE = 'admin/admin_activity/module/attribute';
    public const MODULE_ADMIN_USER = 'admin/admin_activity/module/admin_user';
    public const MODULE_SEO = 'admin/admin_activity/module/seo';

    /**
     * Models that require special "wildcard" handling for activity tracking
     *
     * @var array<class-string>
     */
    private array $wildcardModels = [
        Value::class
    ];

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Config $config
     * @param array<class-string> $additionalWildcardModels Additional wildcard models via DI
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly Config $config,
        array $additionalWildcardModels = []
    ) {
        $this->wildcardModels = array_merge($this->wildcardModels, $additionalWildcardModels);
    }

    /**
     * @inheritDoc
     */
    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::ACTIVITY_ENABLE);
    }

    /**
     * @inheritDoc
     */
    public function isLoginEnabled(): bool
    {
        return $this->isEnabled()
            && $this->scopeConfig->isSetFlag(self::LOGIN_ACTIVITY_ENABLE);
    }

    /**
     * @inheritDoc
     */
    public function isPageVisitEnabled(): bool
    {
        return $this->isEnabled()
            && $this->scopeConfig->isSetFlag(self::PAGE_VISIT_ENABLE);
    }

    /**
     * @inheritDoc
     */
    public function getConfigValue(string $constantName): mixed
    {
        $constantFqn = 'self::' . $constantName;

        if (!defined($constantFqn)) {
            return false;
        }

        $value = $this->scopeConfig->getValue(
            constant($constantFqn),
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );

        return $value ?: false;
    }

    /**
     * @inheritDoc
     */
    public function getActionTranslatedLabel(string $action): string
    {
        return (string)$this->config->getActionLabel($action);
    }

    /**
     * @inheritDoc
     */
    public function getAllActions(): array
    {
        return $this->config->getActions();
    }

    /**
     * @inheritDoc
     */
    public function getActivityModuleName(string $module): string
    {
        return $this->config->getActivityModuleName($module);
    }

    /**
     * @inheritDoc
     */
    public function isWildCardModel(DataObject|string $model): bool
    {
        $className = is_string($model) ? $model : $model::class;
        return in_array($className, $this->wildcardModels, true);
    }
}

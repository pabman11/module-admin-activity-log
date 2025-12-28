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

namespace MageOS\AdminActivityLog\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\DataObject;
use MageOS\AdminActivityLog\Model\Config;

/**
 * Configuration helper for admin activity logging
 *
 * @deprecated Use \MageOS\AdminActivityLog\Api\ActivityConfigInterface instead
 * @see \MageOS\AdminActivityLog\Api\ActivityConfigInterface
 */
class Data extends AbstractHelper
{
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

    /** @var array<class-string> */
    public static array $wildcardModels = [
        Value::class
    ];

    public function __construct(
        Context $context,
        protected readonly Config $config
    ) {
        parent::__construct($context);
    }

    /**
     * Check and return status of module
     *
     * @deprecated Use ActivityConfigInterface::isEnabled() instead
     * @see \MageOS\AdminActivityLog\Api\ActivityConfigInterface::isEnabled()
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->scopeConfig->isSetFlag(self::ACTIVITY_ENABLE);
    }

    /**
     * Check and return status for login activity
     *
     * @deprecated Use ActivityConfigInterface::isLoginEnabled() instead
     * @see \MageOS\AdminActivityLog\Api\ActivityConfigInterface::isLoginEnabled()
     * @return bool
     */
    public function isLoginEnable(): bool
    {
        $status = $this->scopeConfig->isSetFlag(self::ACTIVITY_ENABLE);
        $loginStatus = $this->scopeConfig->isSetFlag(self::LOGIN_ACTIVITY_ENABLE);

        return $status && $loginStatus;
    }

    /**
     * Check and return status for page visit history
     *
     * @deprecated Use ActivityConfigInterface::isPageVisitEnabled() instead
     * @see \MageOS\AdminActivityLog\Api\ActivityConfigInterface::isPageVisitEnabled()
     * @return bool
     */
    public function isPageVisitEnable(): bool
    {
        $status = $this->scopeConfig->isSetFlag(self::ACTIVITY_ENABLE);
        $pageVisitStatus = $this->scopeConfig->isSetFlag(self::PAGE_VISIT_ENABLE);

        return $status && $pageVisitStatus;
    }

    /**
     * Get value of system config from path
     *
     * @deprecated Use ActivityConfigInterface::getConfigValue() instead
     * @see \MageOS\AdminActivityLog\Api\ActivityConfigInterface::getConfigValue()
     * @param string $path
     * @return mixed|false
     */
    public function getConfigValue(string $path)
    {
        $moduleValue = $this->scopeConfig->getValue(
            constant(
                'self::'
                . $path
            ),
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );
        if ($moduleValue) {
            return $moduleValue;
        }
        return false;
    }

    /**
     * Get translated label by action name
     *
     * @deprecated Use ActivityConfigInterface::getActionTranslatedLabel() instead
     * @see \MageOS\AdminActivityLog\Api\ActivityConfigInterface::getActionTranslatedLabel()
     * @param string $action
     * @return string
     */
    public function getActionTranslatedLabel(string $action): string
    {
        return (string)$this->config->getActionLabel($action);
    }

    /**
     * Get all actions
     *
     * @deprecated Use ActivityConfigInterface::getAllActions() instead
     * @see \MageOS\AdminActivityLog\Api\ActivityConfigInterface::getAllActions()
     * @return array
     */
    public function getAllActions(): array
    {
        return $this->config->getActions();
    }

    /**
     * Get activity module name
     *
     * @deprecated Use ActivityConfigInterface::getActivityModuleName() instead
     * @see \MageOS\AdminActivityLog\Api\ActivityConfigInterface::getActivityModuleName()
     */
    public function getActivityModuleName(string $module): string
    {
        return $this->config->getActivityModuleName($module);
    }

    /**
     * Check if model is a wildcard model (system config value)
     *
     * @deprecated Use ActivityConfigInterface::isWildCardModel() instead
     * @see \MageOS\AdminActivityLog\Api\ActivityConfigInterface::isWildCardModel()
     */
    public static function isWildCardModel(DataObject|string $model): bool
    {
        trigger_error(
            'Static method ' . __METHOD__ . ' is deprecated. Use checkIsWildCardModel() instance method instead.',
            E_USER_DEPRECATED
        );
        $className = is_string($model) ? $model : $model::class;
        return in_array($className, self::$wildcardModels, true);
    }

    /**
     * Check if model is a wildcard model (system config value)
     *
     * Instance method replacement for the deprecated static isWildCardModel().
     *
     * @param DataObject|string $model Model instance or class name
     * @return bool True if the model is a wildcard model
     */
    public function checkIsWildCardModel(DataObject|string $model): bool
    {
        $className = is_string($model) ? $model : $model::class;
        return in_array($className, self::$wildcardModels, true);
    }
}

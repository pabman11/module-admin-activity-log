<?php
/**
 * KiwiCommerce
 *
 * Do not edit or add to this file if you wish to upgrade to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please contact us https://kiwicommerce.co.uk/contacts.
 *
 * @category   KiwiCommerce
 * @package    MageOS_AdminActivityLog
 * @copyright  Copyright (C) 2018 Kiwi Commerce Ltd (https://kiwicommerce.co.uk/)
 * @license    https://kiwicommerce.co.uk/magento2-extension-license/
 */

namespace MageOS\AdminActivityLog\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value\Interceptor;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use MageOS\AdminActivityLog\Model\Config;

/**
 * Class Data
 * @package MageOS\AdminActivityLog\Helper
 */
class Data extends AbstractHelper
{
    /**
     * @var string
     */
    public const ACTIVITY_ENABLE = 'admin/admin_activity/general/enable';

    /**
     * @var string
     */
    public const LOGIN_ACTIVITY_ENABLE = 'admin/admin_activity/general/login_activity';

    /**
     * @var string
     */
    public const PAGE_VISIT_ENABLE = 'admin/admin_activity/general/page_visit';

    /**
     * @var string
     */
    public const CLEAR_LOG_DAYS = 'admin/admin_activity/general/clearlog';

    /**
     * @var string
     */
    public const MODULE_ORDER = 'admin/admin_activity/module/order';

    /**
     * @var string
     */
    public const MODULE_PRODUCT = 'admin/admin_activity/module/product';

    /**
     * @var string
     */
    public const MODULE_CATEGORY = 'admin/admin_activity/module/category';

    /**
     * @var string
     */
    public const MODULE_CUSTOMER = 'admin/admin_activity/module/customer';

    /**
     * @var string
     */
    public const MODULE_PROMOTION = 'admin/admin_activity/module/promotion';

    /**
     * @var string
     */
    public const MODULE_EMAIL = 'admin/admin_activity/module/email';

    /**
     * @var string
     */
    public const MODULE_PAGE = 'admin/admin_activity/module/page';

    /**
     * @var string
     */
    public const MODULE_BLOCK = 'admin/admin_activity/module/block';

    /**
     * @var string
     */
    public const MODULE_WIDGET = 'admin/admin_activity/module/widget';

    /**
     * @var string
     */
    public const MODULE_THEME = 'admin/admin_activity/module/theme';

    /**
     * @var string
     */
    public const MODULE_SYSTEM_CONFIG = 'admin/admin_activity/module/system_config';

    /**
     * @var string
     */
    public const MODULE_ATTRIBUTE = 'admin/admin_activity/module/attibute';

    /**
     * @var string
     */
    public const MODULE_ADMIN_USER = 'admin/admin_activity/module/admin_user';

    /**
     * @var string
     */
    public const MODULE_SEO = 'admin/admin_activity/module/seo';

    /**
     * @var Config
     */
    public $config;

    /**
     * @var array
     */
    public static $wildcardModels = [
        Interceptor::class
    ];

    /**
     * Data constructor.
     * @param Context $context
     * @param Config $config
     */
    public function __construct(
        Context $context,
        Config $config
    ) {
        $this->config = $config;
        parent::__construct($context);
    }

    /**
     * Check and return status of module
     * @return bool
     */
    public function isEnable()
    {
        $status = $this->scopeConfig->isSetFlag(self::ACTIVITY_ENABLE, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
        if ($status == '1') {
            return true;
        }

        return false;
    }

    /**
     * Check and return status for login activity
     * @return bool
     */
    public function isLoginEnable()
    {
        $status = $this->scopeConfig->isSetFlag(self::ACTIVITY_ENABLE, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
        $loginStatus = $this->scopeConfig
            ->isSetFlag(self::LOGIN_ACTIVITY_ENABLE, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
        if ($status == '1' && $loginStatus == '1') {
            return true;
        }

        return false;
    }

    /**
     * Check and return status for page visit history
     * @return bool
     */
    public function isPageVisitEnable()
    {
        $status = $this->scopeConfig->isSetFlag(self::ACTIVITY_ENABLE, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
        $pageVisitStatus = $this->scopeConfig
            ->isSetFlag(self::PAGE_VISIT_ENABLE, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
        if ($status == '1' && $pageVisitStatus == '1') {
            return true;
        }

        return false;
    }

    /**
     * Get value of system config from path
     * @param $path
     * @return bool
     */
    public function getConfigValue($path)
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
     * @param string $action
     * @return string
     */
    public function getActionTranslatedLabel($action)
    {
        return $this->config->getActionLabel($action);
    }

    /**
     * Get all actions
     * @return array
     */
    public function getAllActions()
    {
        return $this->config->getActions();
    }

    /**
     * Get activity module name
     * @return bool
     */
    public function getActivityModuleName($module)
    {
        return $this->config->getActivityModuleName($module);
    }

    /**
     * Get module name is valid or not
     * @param $model
     * @return bool
     */
    public static function isWildCardModel($model)
    {
        $model = is_string($model) ? $model : get_class($model);
        if (in_array($model, self::$wildcardModels)) {
            return true;
        }
        return false;
    }
}

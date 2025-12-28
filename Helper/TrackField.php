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

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\DataObject;
use MageOS\AdminActivityLog\Model\Activity\SystemConfig;
use MageOS\AdminActivityLog\Model\Activity\ThemeConfig;
use MageOS\AdminActivityLog\Model\Config;

/**
 * Helper for tracking field changes in admin activity log
 *
 * Skip fields configuration is now managed via adminactivity.xml.
 * This class provides methods to get tracked field data for add/edit/delete operations.
 *
 * @deprecated Use \MageOS\AdminActivityLog\Api\FieldTrackerInterface instead
 * @see \MageOS\AdminActivityLog\Api\FieldTrackerInterface
 */
class TrackField extends AbstractHelper
{
    /**
     * Method name constants for wildcard model detection
     * @deprecated Use skip_fields XML configuration instead
     */
    public const SYSTEM_METHOD = 'getSystemConfigFieldData';
    public const THEME_METHOD = 'getThemeConfigFieldData';
    public const PRODUCT_METHOD = 'getProductFieldData';

    /**
     * Maximum length for stored field values (in bytes)
     * TEXT columns support up to 65,535 bytes; cut off before
     */
    private const MAX_VALUE_LENGTH = 65000;

    public function __construct(
        Context $context,
        protected readonly SystemConfig $systemConfig,
        protected readonly ThemeConfig $themeConfig,
        protected readonly Config $config
    ) {
        parent::__construct($context);
    }

    /**
     * Get product module fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml. This method is kept for backward compatibility.
     */
    public function getProductFieldData(): array
    {
        return [
            'form_key',
            'current_product_id',
            'force_reindex_eav_required',
            'news_from_date_is_formated',
            'can_save_custom_options',
            'save_rewrites_history',
            'is_custom_option_changed',
            'special_from_date_is_formated',
            'custom_design_from_is_formated',
            'affect_product_custom_options',
            'product_has_weight',
            'check_if_is_new',
            'entity_id',
            'updated_at',
            'edit_mode',
            'gift_message_available',
            'use_config_gift_message_available',
            'created_at',
            'is_changed_websites'
        ];
    }

    /**
     * Get category module fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getCategoryFieldData(): array
    {
        return [
            'form_key',
            'updated_at',
            'created_at'
        ];
    }

    /**
     * Get customer module fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getCustomerFieldData(): array
    {
        return [
            'id',
            'attribute_set_id',
            'entity_id',
            'form_key',
            'check_if_is_new',
            'dob_is_formated',
            'updated_at',
            'created_at',
            'rp_token',
            'rp_token_created_at',
            'is_customer_save_transaction',
            'store_id',
            'customer_id',
            'parent_id',
            'force_process'
        ];
    }

    /**
     * Get customer group modules fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getCustomerGroupFieldData(): array
    {
        return [
            'customer_group_id',
            'check_if_is_new'
        ];
    }

    /**
     * Get catalog promotion modules fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getCatalogPromotionFieldData(): array
    {
        return [
            'rule_id',
            'form_key',
            'check_if_is_new'
        ];
    }

    /**
     * Get cart promotion modules fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getCartPromotionFieldData(): array
    {
        return [
            'is_rss',
            'form_key',
            'check_if_is_new',
            'rule_id'
        ];
    }

    /**
     * Get email modules fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getEmailFieldData(): array
    {
        return [
            'template_id',
            'check_if_is_new',
            'form_key',
            'template_actual',
            'code',
            'subject',
            'sender_name',
            'sender_email',
            'text',
            'key'
        ];
    }

    /**
     * Get page modules fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getPageFieldData(): array
    {
        return [
            'page_id',
            'form_key',
            'check_if_is_new',
            'store_code',
            'first_store_id'
        ];
    }

    /**
     * Get block modules fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getBlockFieldData(): array
    {
        return [
            'block_id',
            'form_key',
            'check_if_is_new',
            'store_code'
        ];
    }

    /**
     * Get widget modules fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getWidgetFieldData(): array
    {
        return [
            'check_if_is_new',
            'instance_id'
        ];
    }

    /**
     * Get theme configuration field data
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getThemeConfigFieldData(): array
    {
        return [
            'back',
            'scope',
            'scope_id',
            'form_key',
            'head_includes'
        ];
    }

    /**
     * Get theme schedule field data
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getThemeScheduleFieldData(): array
    {
        return [
            'store_id',
            'check_if_is_new'
        ];
    }

    /**
     * Get system config field data
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getSystemConfigFieldData(): array
    {
        return [
            'check_if_is_new',
        ];
    }

    /**
     * Get attribute modules fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getAttributeFieldData(): array
    {
        return [
            'form_key',
            'check_if_is_new',
            'attribute_id',
            'id',
            'modulePrefix'
        ];
    }

    /**
     * Get attribute set modules fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getAttributeSetFieldData(): array
    {
        return [
            'entity_type_id',
            'check_if_is_new',
            'id'
        ];
    }

    /**
     * Get review rating modules fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getReviewRatingFieldData(): array
    {
        return [
            'rating_id',
            'check_if_is_new'
        ];
    }

    /**
     * Get review modules fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getReviewFieldData(): array
    {
        return [
            'form_key',
            'entity_id',
            'check_if_is_new',
            'review_id',
            'entity_pk_value'
        ];
    }

    /**
     * Get admin user modules fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getAdminUserFieldData(): array
    {
        return [
            'form_key',
            'password_confirmation',
            'current_password',
            'limit',
            'user_roles',
            'check_if_is_new',
            'user_id'
        ];
    }

    /**
     * Get admin user role modules fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getAdminUserRoleFieldData(): array
    {
        return [
            'name',
            'check_if_is_new',
            'role_id'
        ];
    }

    /**
     * Get order modules fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getOrderFieldData(): array
    {
        return [
            'check_if_is_new',
            'created_at',
            'updated_at',
            'entity_id',
            'id',
            'protect_code'
        ];
    }

    /**
     * Get tax rule modules fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getTaxRuleFieldData(): array
    {
        return [
            'form_key',
            'check_if_is_new',
            'id',
            'tax_calculation_rule_id'
        ];
    }

    /**
     * Get tax rate modules fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getTaxRateFieldData(): array
    {
        return [
            'form_key',
            'check_if_is_new',
            'tax_calculation_rate_id'
        ];
    }

    /**
     * Get url rewrites modules fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getUrlRewriteFieldData(): array
    {
        return [
            'url_rewrite_id',
            'store_id'
        ];
    }

    /**
     * Get search term modules fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getSearchTermFieldData(): array
    {
        return [
            'form_key',
            'check_if_is_new',
            'query_id'
        ];
    }

    /**
     * Get search synonyms modules fields
     *
     * @return array{}
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getSearchSynonymsFieldData(): array
    {
        return [];
    }

    /**
     * Get sitemap modules fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getSitemapFieldData(): array
    {
        return [
            'form_key',
            'check_if_is_new',
            'store_id',
            'sitemap_id'
        ];
    }

    /**
     * Get checkout agreement modules fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getCheckoutAgreementFieldData(): array
    {
        return [
            'form_key',
            'check_if_is_new',
            'id'
        ];
    }

    /**
     * Get Order status modules fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getOrderStatusFieldData(): array
    {
        return [
            'form_key',
            'check_if_is_new'
        ];
    }

    /**
     * Get System store modules fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getSystemStoreFieldData(): array
    {
        return [
            'check_if_is_new'
        ];
    }

    /**
     * Get integration modules fields
     *
     * @return string[]
     * @deprecated Skip fields are now configured in adminactivity.xml.
     */
    public function getIntegrationFieldData(): array
    {
        return [
            'form_key',
            'current_password',
            'integration_id',
            'check_if_is_new',
            'consumer_id',
            'consumer_key',
            'consumer_secret',
            'identity_link_url'
        ];
    }

    /**
     * Get global skip edit fields from XML config
     * @return string[]
     */
    public function getSkipEditFieldData(): array
    {
        return $this->config->getGlobalSkipEditFields();
    }

    /**
     * Get skip fields from method name or array
     *
     * Accepts either:
     * - string method name (legacy): calls the method dynamically
     * - array of field names (new): returns the array directly
     *
     * @param string|array $methodOrFields Method name or array of fields
     * @return string[]
     */
    public function getFields(string|array $methodOrFields): array
    {
        // New XML-based config: skip_fields array passed directly
        if (is_array($methodOrFields)) {
            return $methodOrFields;
        }

        // Legacy support: method name passed as string
        if (!empty($methodOrFields) && method_exists($this, $methodOrFields)) {
            return $this->{$methodOrFields}();
        }

        return [];
    }

    /**
     * Get added activity data
     * @param DataObject $model
     * @param string|array $methodOrFields Method name or array of skip fields
     * @return array{}|array<string, array{
     *     old_value: string,
     *     new_value: string
     * }>
     */
    public function getAddData(DataObject $model, string|array $methodOrFields): array
    {
        $skipFieldArray = $this->getFields($methodOrFields);

        $logData = [];
        if (!empty($model->getData()) && is_array($model->getData())) {
            $logData = $this->getWildCardData($model, $methodOrFields);
            foreach ($model->getData() as $key => $value) {
                if ($this->validateValue($model, $key, $value, $skipFieldArray) || empty($value)) {
                    continue;
                }
                $logData[$key] = [
                    'old_value' => '',
                    'new_value' => $this->prepareValue($value)
                ];
            }
        }
        return $logData;
    }

    /**
     * Get edited activity data
     * @param DataObject $model
     * @param string|array $methodOrFields Method name or array of skip fields
     * @return array{}|array<string, array{
     *     old_value: string,
     *     new_value: string
     * }>
     */
    public function getEditData(DataObject $model, string|array $methodOrFields): array
    {
        $fieldArray = $this->getFields($methodOrFields);
        $skipFieldArray = $this->getSkipEditFieldData();

        if (Data::isWildCardModel($model)) {
            // Check for wildcard model types using method name or skip_fields pattern
            $isSystemConfig = $this->isSystemConfigMethod($methodOrFields);
            $isThemeConfig = $this->isThemeConfigMethod($methodOrFields);

            if ($isSystemConfig) {
                return $this->systemConfig->getEditData($model, $fieldArray);
            } elseif ($isThemeConfig) {
                return $this->themeConfig->getEditData($model, $fieldArray);
            }
        }

        $logData = [];
        if (!empty($model->getData()) && is_array($model->getData())) {
            $logData = $this->getWildCardData($model, $methodOrFields);
            $skipFieldArray = array_merge($skipFieldArray, $fieldArray);
            foreach ($model->getData() as $key => $value) {
                if ($this->validateValue($model, $key, $value, $skipFieldArray)) {
                    continue;
                }
                $newData = !empty($value) ? $value : '';
                $oldData = !empty($model->getOrigData($key)) ? $model->getOrigData($key) : '';
                if (!empty($newData) || !empty($oldData)) {
                    if ($newData != $oldData) {
                        $logData[$key] = [
                            'old_value' => $this->prepareValue($oldData),
                            'new_value' => $this->prepareValue($newData)
                        ];
                    }
                }
            }
        }
        return $logData;
    }

    /**
     * Check if method/fields indicates system config
     */
    private function isSystemConfigMethod(string|array $methodOrFields): bool
    {
        if (is_string($methodOrFields)) {
            return $methodOrFields === self::SYSTEM_METHOD;
        }
        // For array (skip_fields), check if it matches system config pattern
        return $methodOrFields === ['check_if_is_new'];
    }

    /**
     * Check if method/fields indicates theme config
     */
    private function isThemeConfigMethod(string|array $methodOrFields): bool
    {
        if (is_string($methodOrFields)) {
            return $methodOrFields === self::THEME_METHOD;
        }
        // For array (skip_fields), check if it matches theme config pattern
        return in_array('head_includes', $methodOrFields, true)
            && in_array('scope', $methodOrFields, true);
    }

    /**
     * Get deleted activity data
     * @param DataObject $model
     * @param string|array $methodOrFields Method name or array of skip fields
     * @return array{}|array<string, array{
     *     old_value: string,
     *     new_value: string
     * }>
     */
    public function getDeleteData(DataObject $model, string|array $methodOrFields): array
    {
        $fieldArray = $this->getFields($methodOrFields);

        $logData = [];
        if (!empty($model->getOrigData()) && is_array($model->getOrigData())) {
            $logData = $this->getWildCardData($model, $methodOrFields);
            foreach ($model->getOrigData() as $key => $value) {
                if ($this->validateValue($model, $key, $value, $fieldArray) || empty($value)) {
                    continue;
                }
                $logData[$key] = [
                    'old_value' => $this->prepareValue($value),
                    'new_value' => ''
                ];
            }
        }
        return $logData;
    }

    /**
     * Get wild data (special field handling like product quantity)
     * @param DataObject $model
     * @param string|array $methodOrFields Method name or array of skip fields
     * @return array{}|array<string, array{
     *     old_value: string,
     *     new_value: string
     * }>
     */
    public function getWildCardData(DataObject $model, string|array $methodOrFields): array
    {
        $logData = [];

        // Check if this is a product method/fields
        $isProductMethod = $this->isProductMethod($methodOrFields);

        if ($isProductMethod) {
            $newQty = $model->getData('stock_data');
            $oldQty = $model->getOrigData('quantity_and_stock_status');
            if (isset($newQty['qty']) && isset($oldQty['qty']) && $newQty['qty'] != $oldQty['qty']) {
                $logData['qty'] = [
                    'old_value' => $this->prepareValue($oldQty['qty']),
                    'new_value' => $this->prepareValue($newQty['qty'])
                ];
            }
        }

        return $logData;
    }

    /**
     * Check if method/fields indicates product module
     */
    private function isProductMethod(string|array $methodOrFields): bool
    {
        if (is_string($methodOrFields)) {
            return $methodOrFields === self::PRODUCT_METHOD;
        }
        // For array (skip_fields), check if it matches product pattern
        return in_array('current_product_id', $methodOrFields, true)
            || in_array('product_has_weight', $methodOrFields, true);
    }

    /**
     * Skip this fields while tracking activity log
     */
    public function validateValue(DataObject $model, string $key, mixed $value, array $skipFields): bool
    {
        if (is_array($value) || is_object($value) || is_array($model->getOrigData($key))
            || in_array($key, $skipFields)) {
            return true;
        }
        return false;
    }

    /**
     * Truncate a value if it exceeds the maximum length
     *
     * Prevents database bloat by limiting the size of stored field values.
     * Large values (e.g., serialized arrays, long text content) are truncated
     * with an indicator that truncation occurred.
     *
     * @param mixed $value The value to truncate
     * @return string The truncated string value
     */
    public function truncateValue(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        // Convert to string if not already
        if (!is_string($value)) {
            if (is_array($value) || is_object($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                if ($value === false) {
                    return '[unserializable]';
                }
            } else {
                $value = (string)$value;
            }
        }

        if (strlen($value) > self::MAX_VALUE_LENGTH) {
            return substr($value, 0, self::MAX_VALUE_LENGTH - 20) . '... [truncated]';
        }

        return $value;
    }

    /**
     * Prepare a value for storage, applying truncation
     *
     * @param mixed $value Raw value from model
     * @return string Prepared value ready for storage
     */
    private function prepareValue(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        return $this->truncateValue($value);
    }
}

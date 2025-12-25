<?php
declare(strict_types=1);
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

namespace MageOS\AdminActivityLog\Model;

use Magento\Framework\Model\AbstractModel;
use MageOS\AdminActivityLog\Api\Data\ActivityLogInterface;

class ActivityLog extends AbstractModel implements ActivityLogInterface
{
    public const ACTIVITYLOG_ID = 'entity_id';

    public function _construct(): void
    {
        $this->_init(ResourceModel\ActivityLog::class);
    }

    public function getActivityId(): ?int
    {
        $value = $this->getData(self::ACTIVITY_ID);
        return $value === null ? null : (int)$value;
    }

    public function setActivityId(?int $activityId): ActivityLogInterface
    {
        return $this->setData(self::ACTIVITY_ID, $activityId);
    }

    public function getFieldName(): string
    {
        return (string)$this->getData(self::FIELD_NAME);
    }

    public function setFieldName(string $fieldName): ActivityLogInterface
    {
        return $this->setData(self::FIELD_NAME, $fieldName);
    }

    public function getOldValue(): ?string
    {
        $value = $this->getData(self::OLD_VALUE);
        return $value === null ? null : (string)$value;
    }

    public function setOldValue(?string $oldValue): ActivityLogInterface
    {
        return $this->setData(self::OLD_VALUE, $oldValue);
    }

    public function getNewValue(): ?string
    {
        $value = $this->getData(self::NEW_VALUE);
        return $value === null ? null : (string)$value;
    }

    public function setNewValue(?string $newValue): ActivityLogInterface
    {
        return $this->setData(self::NEW_VALUE, $newValue);
    }
}

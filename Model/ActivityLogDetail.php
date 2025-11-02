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
use MageOS\AdminActivityLog\Api\Data\ActivityLogDetailInterface;

class ActivityLogDetail extends AbstractModel implements ActivityLogDetailInterface
{
    public const ACTIVITYLOGDETAIL_ID = 'entity_id';

    public function _construct(): void
    {
        $this->_init(ResourceModel\ActivityLogDetail::class);
    }

    public function getActivityId(): int
    {
        return (int)$this->getData(self::ACTIVITY_ID);
    }

    public function setActivityId(int $activityId): ActivityLogDetailInterface
    {
        return $this->setData(self::ACTIVITY_ID, $activityId);
    }

    public function getModelClass(): string
    {
        return (string)$this->getData(self::MODEL_CLASS);
    }

    public function setModelClass(string $modelClass): ActivityLogDetailInterface
    {
        return $this->setData(self::MODEL_CLASS, $modelClass);
    }

    public function getItemId(): ?int
    {
        $value = $this->getData(self::ITEM_ID);
        return $value === null ? null : (int)$value;
    }

    public function setItemId(?int $itemId): ActivityLogDetailInterface
    {
        return $this->setData(self::ITEM_ID, $itemId);
    }

    public function getStatus(): string
    {
        return (string)$this->getData(self::STATUS);
    }

    public function setStatus(string $status): ActivityLogDetailInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    public function getResponse(): string
    {
        return (string)$this->getData(self::RESPONSE);
    }

    public function setResponse(string $response): ActivityLogDetailInterface
    {
        return $this->setData(self::RESPONSE, $response);
    }
}

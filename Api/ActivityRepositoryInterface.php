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

namespace MageOS\AdminActivityLog\Api;

use Magento\Framework\DataObject;
use MageOS\AdminActivityLog\Model\Activity;
use MageOS\AdminActivityLog\Model\ActivityLogDetail;
use MageOS\AdminActivityLog\Model\ResourceModel\Activity\Collection;
use MageOS\AdminActivityLog\Model\ResourceModel\ActivityLog\Collection as ActivityLogCollection;

/**
 * Interface ActivityRepositoryInterface
 * @package MageOS\AdminActivityLog\Api
 */
interface ActivityRepositoryInterface
{
    /**
     * Array of protected fields
     * @return array
     */
    public function protectedFields(): array;

    /**
     * Get collection of admin activity
     * @return Collection
     */
    public function getList();

    /**
     * Get all admin activity data before date
     * @param $endDate
     * @return Collection
     */
    public function getListBeforeDate($endDate);

    /**
     * Remove activity log entry
     * @param int $activityId
     * @return void
     */
    public function deleteActivityById($activityId): void;

    /**
     * Get all admin activity detail by activity id
     * @param int $activityId
     * @return ActivityLogDetail
     */
    public function getActivityDetail($activityId): ActivityLogDetail;

    /**
     * Get all admin activity log by activity id
     * @param int $activityId
     * @return ActivityLogCollection
     */
    public function getActivityLog($activityId): ActivityLogCollection;

    /**
     * Revert last changes made in module
     * @param Activity $activity
     * @return bool
     */
    public function revertActivity(Activity $activity): bool;

    /**
     * Get old data for system config module
     * @param DataObject $model
     * @return mixed
     */
    public function getOldData(DataObject $model);

    /**
     * Get admin activity by id
     * @param int $activityId
     * @return Activity
     */
    public function getActivityById($activityId): Activity;

    /**
     * Check field is protected or not
     * @param string $fieldName
     * @return bool
     */
    public function isFieldProtected(string $fieldName): bool;
}

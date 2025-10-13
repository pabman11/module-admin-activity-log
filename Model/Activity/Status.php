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

namespace MageOS\AdminActivityLog\Model\Activity;

use Magento\Framework\Model\AbstractModel;
use MageOS\AdminActivityLog\Model\ActivityFactory;

/**
 * Class Status
 * @package MageOS\AdminActivityLog\Model\Activity
 */
class Status extends AbstractModel
{
    /**
     * @var Int
     */
    public const ACTIVITY_NONE = 0;

    /**
     * @var Int
     */
    public const ACTIVITY_REVERTABLE = 1;

    /**
     * @var Int
     */
    public const ACTIVITY_REVERT_SUCCESS = 2;

    /**
     * @var Int
     */
    public const ACTIVITY_FAIL = 3;

    /**
     * @var ActivityFactory
     */
    public $activityFactory;

    /**
     * Status constructor.
     * @param ActivityFactory $activityFactory
     */
    public function __construct(
        ActivityFactory $activityFactory
    ) {
        $this->activityFactory = $activityFactory;
    }

    /**
     * Set success revert status
     * @param $activityId
     * @return void
     */
    public function markSuccess($activityId)
    {
        $activityModel = $this->activityFactory->create()->load($activityId);
        $activityModel->setIsRevertable(self::ACTIVITY_REVERT_SUCCESS);
        $activityModel->save();
    }

    /**
     * Set fail revert status
     * @param $activityId
     * @return void
     */
    public function markFail($activityId)
    {
        $activityModel = $this->activityFactory->create()->load($activityId);
        $activityModel->setIsRevertable(self::ACTIVITY_FAIL);
        $activityModel->save();
    }
}

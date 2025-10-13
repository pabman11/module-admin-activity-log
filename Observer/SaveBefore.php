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

namespace MageOS\AdminActivityLog\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use MageOS\AdminActivityLog\Api\ActivityRepositoryInterface;
use MageOS\AdminActivityLog\Helper\Benchmark;
use MageOS\AdminActivityLog\Helper\Data as Helper;
use MageOS\AdminActivityLog\Model\Processor;

/**
 * Class SaveBefore
 * @package MageOS\AdminActivityLog\Observer
 */
class SaveBefore implements ObserverInterface
{
    /**
     * @var Helper
     */
    public $helper;

    /**
     * @var Processor
     */
    public $processor;

    /**
     * @var ActivityRepositoryInterface
     */
    public $activityRepository;

    /**
     * @var Benchmark
     */
    public $benchmark;

    /**
     * SaveBefore constructor.
     * @param Helper $helper
     * @param Processor $processor
     * @param ActivityRepositoryInterface $activityRepository
     * @param Benchmark $banchmark
     */
    public function __construct(
        Helper $helper,
        Processor $processor,
        ActivityRepositoryInterface $activityRepository,
        Benchmark $benchmark
    ) {
        $this->helper = $helper;
        $this->processor = $processor;
        $this->activityRepository = $activityRepository;
        $this->benchmark = $benchmark;
    }

    /**
     * Save before
     * @param Observer $observer
     * @return Observer
     */
    public function execute(Observer $observer)
    {
        $this->benchmark->start(__METHOD__);

        if (!$this->helper->isEnable()) {
            return $observer;
        }

        $object = $observer->getEvent()->getObject();
        if ($object->getId() == 0) {
            $object->setCheckIfIsNew(true);
        } else {
            $object->setCheckIfIsNew(false);
            if ($this->processor->validate($object)) {
                $origData = $object->getOrigData();
                if (!empty($origData)) {
                    return $observer;
                }
                $data = $this->activityRepository->getOldData($object);
                foreach ($data->getData() as $key => $value) {
                    $object->setOrigData($key, $value);
                }
            }
        }
        $this->benchmark->end(__METHOD__);
        return $observer;
    }
}

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
use MageOS\AdminActivityLog\Helper\Benchmark;
use MageOS\AdminActivityLog\Helper\Data;
use MageOS\AdminActivityLog\Model\Processor;

/**
 * Class LoadAfter
 * @package MageOS\AdminActivityLog\Observer
 */
class LoadAfter implements ObserverInterface
{
    /**
     * LoadAfter constructor.
     * @param Processor $processor
     * @param Data $helper
     * @param Benchmark $benchmark
     */
    public function __construct(
        protected readonly Processor $processor,
        protected readonly Data $helper,
        protected readonly Benchmark $benchmark
    ) {
    }

    /**
     * Delete after
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $this->benchmark->start(__METHOD__);
        if (!$this->helper->isEnable()) {
            return;
        }
        $object = $observer->getEvent()->getObject();
        $this->processor->modelLoadAfter($object);
        $this->benchmark->end(__METHOD__);
    }
}

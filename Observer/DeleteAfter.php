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
use MageOS\AdminActivityLog\Helper\Data as Helper;
use MageOS\AdminActivityLog\Model\Processor;

/**
 * Class DeleteAfter
 * @package MageOS\AdminActivityLog\Observer
 */
class DeleteAfter implements ObserverInterface
{
    public const SYSTEM_CONFIG = 'adminhtml_system_config_save';

    /**
     * DeleteAfter constructor.
     * @param Processor $processor
     * @param Helper $helper
     * @param Benchmark $benchmark
     */
    public function __construct(
        protected readonly Processor $processor,
        protected readonly Helper $helper,
        protected readonly Benchmark $benchmark
    ) {
    }

    /**
     * Delete after
     *
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
        if ($this->processor->validate($object) && ($this->processor->getInitAction() === self::SYSTEM_CONFIG)) {
            $this->processor->modelEditAfter($object);
        }
        $this->processor->modelDeleteAfter($object);
        $this->benchmark->end(__METHOD__);
    }
}

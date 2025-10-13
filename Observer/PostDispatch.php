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
 * Class PostDispatch
 * @package MageOS\AdminActivityLog\Observer
 */
class PostDispatch implements ObserverInterface
{
    /**
     * @var Processor
     */
    private $processor;

    /**
     * @var Helper
     */
    public $helper;

    /**
     * @var Benchmark
     */
    public $benchmark;

    /**
     * PostDispatch constructor.
     * @param Processor $processor
     * @param Helper $helper
     * @param Benchmark $benchmark
     */
    public function __construct(
        Processor $processor,
        Helper $helper,
        Benchmark $benchmark
    ) {
        $this->processor = $processor;
        $this->helper = $helper;
        $this->benchmark = $benchmark;
    }

    /**
     * Post dispatch
     * @param Observer $observer
     * @return Observer
     */
    public function execute(Observer $observer)
    {
        $this->benchmark->start(__METHOD__);
        if (!$this->helper->isEnable()) {
            return $observer;
        }
        $this->processor->saveLogs();
        $this->benchmark->end(__METHOD__);
    }
}

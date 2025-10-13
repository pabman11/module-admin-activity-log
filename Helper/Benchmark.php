<?php
/**
 * KiwiCommerce
 *
 * Do not edit or add to this file if you wish to upgrade to newer versions in the future.
 * If you wish to customise this module for your needs.
 * Please contact us https://kiwicommerce.co.uk/contacts.
 *
 * @category   KiwiCommerce
 * @package    MageOS_AdminActivityLog
 * @copyright  Copyright (C) 2018 Kiwi Commerce Ltd (https://kiwicommerce.co.uk/)
 * @license    https://kiwicommerce.co.uk/magento2-extension-license/
 */

namespace MageOS\AdminActivityLog\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Profiler;
use MageOS\AdminActivityLog\Logger\Logger;

/**
 * Class Benchmark
 * @package MageOS\AdminActivityLog\Helper
 */
class Benchmark extends AbstractHelper
{

    /**
     * Get Benchmark is enable or not
     */
    public const BENCHMARK_ENABLE = 1;

    /**
     * @var Logger
     */
    public $logger;

    /**
     * @var String[] Start time of execution
     */
    public $startTime;

    /**
     * @var String[] End time of execution
     */
    public $endTime;

    /**
     * Benchmark constructor.
     * @param Context $context
     * @param Logger $logger
     */
    public function __construct(
        Context $context,
        Logger $logger
    ) {
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * log info about start time in millisecond
     * @param $method
     * @return void
     */
    public function start($method)
    {
        $this->reset($method);
        if (self::BENCHMARK_ENABLE) {
            $this->startTime[$method] = round(microtime(true) * 1000);
            $this->logger->info("Method: " . $method);
            $this->logger->info("Start time: " . $this->startTime[$method]);
            Profiler::start($method);
        }
    }

    /**
     * log info about end time and time diiference in millisecond
     * @param $method
     * @return void
     */
    public function end($method)
    {
        if (self::BENCHMARK_ENABLE) {
            $this->endTime[$method] = round(microtime(true) * 1000);
            $difference = $this->endTime[$method] - $this->startTime[$method];
            if ($difference) {
                $this->logger->info("Method: " . $method);
                $this->logger->info("Ends time: " . $this->endTime[$method]);
                $this->logger->info("Time difference in millisecond: " . $difference);
            }
            Profiler::stop($method);
        }
    }

    /**
     * Reset start time and end time
     * @param $method
     * @return void
     */
    public function reset($method)
    {
        $this->startTime[$method] = 0;
        $this->endTime[$method] = 0;
    }
}

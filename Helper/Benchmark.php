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
use Magento\Framework\Profiler;
use Psr\Log\LoggerInterface;

/**
 * Benchmark helper for performance profiling
 */
class Benchmark extends AbstractHelper
{
    /**
     * Config path for benchmark enable flag
     */
    public const XML_PATH_BENCHMARK_ENABLE = 'admin/admin_activity/developer/benchmark_enable';

    /**
     * @var String[] Start time of execution
     */
    private array $startTime = [];

    /**
     * @var String[] End time of execution
     */
    private array $endTime = [];

    public function __construct(
        Context $context,
        protected LoggerInterface $logger
    ) {
        parent::__construct($context);
    }

    /**
     * Check if benchmark logging is enabled
     */
    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_BENCHMARK_ENABLE);
    }

    /**
     * Log info about start time in millisecond
     */
    public function start(string $method): void
    {
        $this->reset($method);
        if ($this->isEnabled()) {
            $this->startTime[$method] = round(microtime(true) * 1000);
            $this->logger->info("Method: " . $method);
            $this->logger->info("Start time: " . $this->startTime[$method]);
            Profiler::start($method);
        }
    }

    /**
     * Log info about end time and time difference in millisecond
     */
    public function end(string $method): void
    {
        if ($this->isEnabled()) {
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
     */
    public function reset(string $method): void
    {
        $this->startTime[$method] = 0;
        $this->endTime[$method] = 0;
    }
}

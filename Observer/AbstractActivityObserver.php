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

namespace MageOS\AdminActivityLog\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use MageOS\AdminActivityLog\Helper\Benchmark;
use MageOS\AdminActivityLog\Helper\Data as Helper;

/**
 * Abstract base class for admin activity observers
 *
 * Encapsulates common observer patterns:
 * - Module enable check
 * - Benchmark timing
 * - Error handling
 */
abstract class AbstractActivityObserver implements ObserverInterface
{
    public function __construct(
        protected readonly Helper $helper,
        protected readonly Benchmark $benchmark
    ) {
    }

    /**
     * Execute observer with standard enable check and benchmarking
     */
    public function execute(Observer $observer): void
    {
        if (!$this->isEnabled()) {
            return;
        }

        $this->benchmark->start(static::class);

        try {
            $this->process($observer);
        } finally {
            $this->benchmark->end(static::class);
        }
    }

    /**
     * Check if the module functionality is enabled
     *
     * Override in subclasses for different enable checks (e.g., login-specific)
     */
    protected function isEnabled(): bool
    {
        return $this->helper->isEnable();
    }

    /**
     * Process the observer event
     *
     * Implement this method in subclasses to handle the specific event logic
     */
    abstract protected function process(Observer $observer): void;
}

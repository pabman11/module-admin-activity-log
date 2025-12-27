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
use MageOS\AdminActivityLog\Helper\Benchmark;
use MageOS\AdminActivityLog\Helper\Data as Helper;
use MageOS\AdminActivityLog\Model\Processor;

class SaveAfter extends AbstractActivityObserver
{
    public const ACTION_MASSCANCEL = 'massCancel';
    public const SYSTEM_CONFIG = 'adminhtml_system_config_save';

    public function __construct(
        Helper $helper,
        Benchmark $benchmark,
        private readonly Processor $processor
    ) {
        parent::__construct($helper, $benchmark);
    }

    protected function process(Observer $observer): void
    {
        $object = $observer->getEvent()->getObject();
        if ($object->getCheckIfIsNew()) {
            if ($this->processor->getInitAction() === self::SYSTEM_CONFIG) {
                $this->processor->modelEditAfter($object);
            }
            $this->processor->modelAddAfter($object);
        } elseif ($this->processor->validate($object)) {
            if ($this->processor->getEventConfig('action') === self::ACTION_MASSCANCEL) {
                $this->processor->modelDeleteAfter($object);
            }
            $this->processor->modelEditAfter($object);
        }
    }
}

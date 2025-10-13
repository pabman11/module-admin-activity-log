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

namespace MageOS\AdminActivityLog\Plugin\App;

use Magento\Framework\Interception\InterceptorInterface;
use MageOS\AdminActivityLog\Helper\Benchmark;
use MageOS\AdminActivityLog\Model\Processor;

/**
 * Class Action
 * @package MageOS\AdminActivityLog\Plugin\App
 */
class Action
{
    /**
     * @var Processor
     */
    public $processor;

    /**
     * @var Benchmark
     */
    public $benchmark;

    /**
     * Action constructor.
     * @param Processor $processor
     * @param Benchmark $benchmark
     */
    public function __construct(
        Processor $processor,
        Benchmark $benchmark
    ) {
        $this->processor = $processor;
        $this->benchmark = $benchmark;
    }

    /**
     * Get before dispatch data
     * @param InterceptorInterface $controller
     * @return void
     */
    public function beforeDispatch(InterceptorInterface $controller)
    {
        $this->benchmark->start(__METHOD__);
        $actionName = $controller->getRequest()->getActionName();
        $fullActionName = $controller->getRequest()->getFullActionName();

        $this->processor->init($fullActionName, $actionName);
        $this->processor->addPageVisitLog($controller->getRequest()->getModuleName());
        $this->benchmark->end(__METHOD__);
    }
}

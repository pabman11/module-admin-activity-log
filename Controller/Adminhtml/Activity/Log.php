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

namespace MageOS\AdminActivityLog\Controller\Adminhtml\Activity;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\View\LayoutFactory;
use MageOS\AdminActivityLog\Block\Adminhtml\ActivityLogListing;

/**
 * Class Log
 * @package MageOS\AdminActivityLog\Controller\Adminhtml\Activity
 */
class Log extends Action
{
    /**
     * @var RawFactory
     */
    public $resultRawFactory;

    /**
     * @var LayoutFactory
     */
    public $layoutFactory;

    /**
     * Log constructor.
     * @param Context $context
     * @param RawFactory $resultRawFactory
     * @param LayoutFactory $layoutFactory
     */
    public function __construct(
        Context $context,
        RawFactory $resultRawFactory,
        LayoutFactory $layoutFactory
    ) {
        $this->resultRawFactory = $resultRawFactory;
        $this->layoutFactory = $layoutFactory;

        parent::__construct($context);
    }

    /**
     * view action
     * @return $this
     */
    public function execute()
    {
        $content = $this->layoutFactory->create()
            ->createBlock(
                ActivityLogListing::class
            );

        /** @var Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw->setContents($content->toHtml());
    }
}

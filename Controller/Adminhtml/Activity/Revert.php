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
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\Result\JsonFactory;
use MageOS\AdminActivityLog\Model\Processor;

/**
 * Class Revert
 * @package MageOS\AdminActivityLog\Controller\Adminhtml\Activity
 */
class Revert extends Action
{
    /**
     * @var JsonFactory
     */
    public $resultJsonFactory;

    /**
     * @var Processor
     */
    public $processor;

    /**
     * Revert constructor.
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param Processor $processor
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Processor $processor
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->processor = $processor;
    }

    /**
     * Revert action
     * @return Page
     */
    public function execute()
    {
        $activityId = $this->getRequest()->getParam('id');
        $result = $this->processor->revertActivity($activityId);
        return $this->resultJsonFactory->create()->setData($result);
    }
}

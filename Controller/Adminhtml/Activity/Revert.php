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
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use MageOS\AdminActivityLog\Model\Processor;

/**
 * Class Revert
 * @package MageOS\AdminActivityLog\Controller\Adminhtml\Activity
 */
class Revert extends Action
{
    /**
     * Revert constructor.
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param Processor $processor
     */
    public function __construct(
        Context $context,
        protected readonly JsonFactory $resultJsonFactory,
        protected readonly Processor $processor
    ) {
        parent::__construct($context);
    }

    /**
     * Revert action
     * @return Json
     */
    public function execute()
    {
        $activityId = $this->getRequest()->getParam('id');

        $result = $this->processor->revertActivity($activityId);

        $json = $this->resultJsonFactory->create();
        $json->setData($result);

        return $json;
    }
}

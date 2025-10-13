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

namespace MageOS\AdminActivityLog\Controller\Adminhtml\Login;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 * @package MageOS\AdminActivityLog\Controller\Adminhtml\Login
 */
class Index extends Action
{
    /**
     * @var string
     */
    public const ADMIN_RESOURCE = 'MageOS_AdminActivityLog::login_activity';

    /**
     * @var PageFactory
     */
    public $resultPageFactory;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     * @return Page
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('MageOS_AdminActivityLog::login_activity');
        $resultPage->addBreadcrumb(__('KiwiCommerce'), __('Login Activity'));
        $resultPage->getConfig()->getTitle()->prepend(__('Login Activity'));

        return $resultPage;
    }
}

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

namespace MageOS\AdminActivityLog\Controller\Adminhtml\Activity;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\View\LayoutFactory;
use MageOS\AdminActivityLog\Block\Adminhtml\ActivityLogListing;

/**
 * Display activity log details in modal
 */
class Log extends Action implements HttpGetActionInterface
{
    /**
     * ACL resource - uses same permission as viewing activity list
     */
    public const ADMIN_RESOURCE = 'MageOS_AdminActivityLog::activity';

    public function __construct(
        Context $context,
        private readonly RawFactory $resultRawFactory,
        private readonly LayoutFactory $layoutFactory
    ) {
        parent::__construct($context);
    }

    /**
     * Render activity log details
     *
     * @return Raw
     */
    public function execute(): Raw
    {
        $content = $this->layoutFactory->create()->createBlock(ActivityLogListing::class);

        /** @var Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw->setContents($content->toHtml());
    }
}

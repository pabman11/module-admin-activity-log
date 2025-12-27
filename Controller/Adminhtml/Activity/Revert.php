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
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use MageOS\AdminActivityLog\Model\Processor;

/**
 * Revert admin activity changes
 *
 * Security: This action requires the 'activity_revert' ACL permission,
 * enforces POST-only requests via HttpPostActionInterface, and validates
 * CSRF tokens through Magento's built-in form_key validation.
 */
class Revert extends Action implements HttpPostActionInterface
{
    /**
     * ACL resource for reverting activity changes
     * Separate from view permission as revert is a destructive operation
     */
    public const ADMIN_RESOURCE = 'MageOS_AdminActivityLog::activity_revert';

    public function __construct(
        Context $context,
        private readonly JsonFactory $resultJsonFactory,
        private readonly Processor $processor
    ) {
        parent::__construct($context);
    }

    /**
     * Revert activity changes
     *
     * @return Json
     */
    public function execute(): Json
    {
        $activityId = $this->getRequest()->getParam('id');
        $activityId = is_numeric($activityId) ? (int)$activityId : 0;

        if ($activityId <= 0) {
            $json = $this->resultJsonFactory->create();
            $json->setData([
                'error' => true,
                'message' => __('Invalid activity ID provided')
            ]);
            return $json;
        }

        $result = $this->processor->revertActivity($activityId);

        $json = $this->resultJsonFactory->create();
        $json->setData($result);

        return $json;
    }
}

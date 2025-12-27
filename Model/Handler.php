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

namespace MageOS\AdminActivityLog\Model;

use Magento\Framework\App\Request\Http;
use Magento\Framework\DataObject;
use Magento\Framework\HTTP\Header;
use Magento\Framework\UrlInterface;
use MageOS\AdminActivityLog\Helper\TrackField as Helper;

/**
 * Class Handler
 * @package MageOS\AdminActivityLog\Model
 */
class Handler
{
    /**
     * Handler constructor.
     * @param Helper $helper
     * @param Header $header
     * @param Http $request
     * @param UrlInterface $urlInterface
     * @param ActivityLogFactory $activityLogFactory
     */
    public function __construct(
        protected readonly Helper $helper,
        protected readonly Header $header,
        protected readonly Http $request,
        protected readonly UrlInterface $urlInterface,
        protected readonly ActivityLogFactory $activityLogFactory
    ) {
    }

    /**
     * Set log data
     * @param array $logs
     * @return ActivityLog[]
     */
    public function initLog(array $logs): array
    {
        if (!empty($logs)) {
            foreach ($logs as $field => $value) {
                $log = $this->activityLogFactory->create()->setData($value);
                $log->setFieldName($field);
                $logs[$field] = $log;
            }
        }
        return $logs;
    }

    /**
     * Get add activity log data
     * @param DataObject $model
     * @param string|array $methodOrFields Method name or array of skip fields
     * @return array
     */
    public function modelAdd(DataObject $model, string|array $methodOrFields): array
    {
        return $this->initLog(
            $this->helper->getAddData($model, $methodOrFields)
        );
    }

    /**
     * Get edit activity log data
     * @param DataObject $model
     * @param string|array $methodOrFields Method name or array of skip fields
     * @return array
     */
    public function modelEdit(DataObject $model, string|array $methodOrFields): array
    {
        return $this->initLog(
            $this->helper->getEditData($model, $methodOrFields)
        );
    }

    /**
     * Get delete activity log data
     * @param DataObject $model
     * @param string|array $methodOrFields Method name or array of skip fields
     * @return array
     */
    public function modelDelete(DataObject $model, string|array $methodOrFields): array
    {
        return $this->initLog(
            $this->helper->getDeleteData($model, $methodOrFields)
        );
    }

    public function getRequest(): Http
    {
        return $this->request;
    }

    public function getHeader(): Header
    {
        return $this->header;
    }
}

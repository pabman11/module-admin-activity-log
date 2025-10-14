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
     * @param string $method
     * @return array
     */
    public function modelAdd(DataObject $model, string $method): array
    {
        return $this->initLog(
            $this->helper->getAddData($model, $method)
        );
    }

    /**
     * Get edit activity log data
     * @param DataObject $model
     * @param string $method
     * @return array
     */
    public function modelEdit(DataObject $model, string $method): array
    {
        return $this->initLog(
            $this->helper->getEditData($model, $method)
        );
    }

    /**
     * Get delete activity log data
     * @param DataObject $model
     * @param string $method
     * @return array
     */
    public function modelDelete(DataObject $model, string $method): array
    {
        return $this->initLog(
            $this->helper->getDeleteData($model, $method)
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

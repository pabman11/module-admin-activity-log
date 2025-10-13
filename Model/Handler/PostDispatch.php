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

namespace MageOS\AdminActivityLog\Model\Handler;

use Magento\Backend\Model\Session;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use MageOS\AdminActivityLog\Model\Activity;
use MageOS\AdminActivityLog\Model\ActivityLog;
use MageOS\AdminActivityLog\Model\ActivityLogDetail;

/**
 * Class PostDispatch
 * @package MageOS\AdminActivityLog\Model\Handler
 */
class PostDispatch
{
    /**
     * Request
     *
     * @var RequestInterface
     */
    public $request;

    /**
     * Response
     *
     * @var ResponseInterface
     */
    public $response;

    /**
     * @var ProductRepositoryInterface
     */
    public $productRepository;

    /**
     * @var Session
     */
    public $session;

    /**
     * PostDispatch constructor.
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param ProductRepositoryInterface $productRepository
     * @param Session $session
     */
    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        ProductRepositoryInterface $productRepository,
        Session $session
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->productRepository = $productRepository;
        $this->session = $session;
    }

    /**
     * @param $model
     * @return array
     */
    public function getProductAttributes($model)
    {
        $logData = [];
        $status = $this->request->getParam('status', '');
        if ($status != '') {
            $logData['status'] = [
                'old_value' => $model->getStatus(),
                'new_value' => $status
            ];
        }

        $attributes = $this->request->getParam('attributes', []);
        if (!empty($attributes)) {
            foreach ($attributes as $attribute => $value) {
                $logData[$attribute] = [
                    'old_value' => $model->getData($attribute),
                    'new_value' => $value
                ];
            }
        }

        $inventories = $this->request->getParam('inventory', []);
        if (!empty($inventories)) {
            foreach ($inventories as $field => $value) {
                $logData[$field] = [
                    'old_value' => $model->getData($field),
                    'new_value' => $value
                ];
            }
        }

        $websiteIds = $this->request->getParam('remove_website', []);
        if ($websiteIds) {
            $logData['remove_website_ids'] = [
                'old_value' => '[]',
                'new_value' => implode(', ', $websiteIds)
            ];
        }

        $websiteIds = $this->request->getParam('add_website', []);
        if ($websiteIds) {
            $logData['add_website_ids'] = [
                'old_value' => '[]',
                'new_value' => implode(', ', $websiteIds)
            ];
        }

        return $logData;
    }

    /**
     * Set product update activity log
     * @param $config
     * @param $processor
     */
    public function productUpdate($config, $processor)
    {
        $activity = $processor->_initLog();
        $activity->setIsRevertable(1);

        $selected = $this->request->getParam('selected');
        if (empty($selected)) {
            $selected = $this->session->getProductIds();
        }
        if (!empty($selected)) {
            foreach ($selected as $id) {
                $model = $this->productRepository->getById($id);

                $log = clone $activity;
                $log->setItemName($model->getData($processor->config->getActivityModuleItemField($config['module'])));
                $log->setItemUrl($processor->getEditUrl($model));

                $logData = $processor->handler->__initLog($this->getProductAttributes($model));
                $logDetail = $processor->_initActivityDetail($model);

                $processor->activityLogs[] = [
                    Activity::class => $log,
                    ActivityLog::class => $logData,
                    ActivityLogDetail::class => $logDetail
                ];
            }
        }
    }
}

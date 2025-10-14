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

namespace MageOS\AdminActivityLog\Model\Activity;

use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Config\ValueFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObject;
use MageOS\AdminActivityLog\Api\Activity\ModelInterface;

/**
 * Class ThemeConfig
 * @package MageOS\AdminActivityLog\Model\Activity
 */
class ThemeConfig implements ModelInterface
{
    /**
     * ThemeConfig constructor.
     * @param DataObject $dataObject
     * @param ValueFactory $valueFactory
     * @param RequestInterface $request
     * @param WriterInterface $configWriter
     */
    public function __construct(
        protected readonly DataObject $dataObject,
        protected readonly ValueFactory $valueFactory,
        protected readonly RequestInterface $request,
        protected readonly WriterInterface $configWriter
    ) {
    }

    /**
     * Get config path of theme configuration
     * @param DataObject $model
     * @return string
     */
    public function getPath(DataObject $model): string
    {
        if ($model->getData('path')) {
            return current(
                explode(
                    '/',
                    (string)$model->getData('path')
                )
            );
        }
        return '';
    }

    /**
     * Get old activity data of theme configuration
     * @param DataObject $model
     * @return mixed
     */
    public function getOldData(DataObject $model)
    {
        $path = $this->getPath($model);
        $systemData = $this->valueFactory->create()->getCollection()
            ->addFieldToFilter('path', ['like' => $path . '/%']);

        $data = [];
        foreach ($systemData->getData() as $config) {
            $path = str_replace('design_', '', str_replace('/', '_', $config['path']));
            $data[$path] = $config['value'];
        }
        return $data;
    }

    /**
     * Get edit activity data of theme configuration
     * @param DataObject $model
     * @param array $fieldArray
     * @return mixed
     */
    public function getEditData(DataObject $model, $fieldArray)
    {
        $path = 'stores/scope_id/' . $model->getScopeId();
        $oldData = $this->getOldData($model);
        $newData = $this->request->getPostValue();
        $result = $this->collectAdditionalData($oldData, $newData, $fieldArray);
        $model->setConfig('Theme Configuration');
        $model->setId($path);
        return $result;
    }

    /**
     * Get revert activity data of theme configuration
     * @param $logData
     * @param $scopeId
     * @param $scope
     * @return bool
     */
    public function revertData($logData, $scopeId, $scope)
    {
        if (!empty($logData)) {
            foreach ($logData as $log) {
                $this->configWriter->save(
                    $log->getFieldName(),
                    $log->getOldValue(),
                    $scope,
                    $scopeId
                );
            }
        }
        return true;
    }

    /**
     * Set additional data
     * @param $oldData
     * @param $newData
     * @param $fieldArray
     * @return array
     */
    public function collectAdditionalData($oldData, $newData, $fieldArray): array
    {
        $logData = [];
        foreach ($newData as $key => $value) {
            if (in_array($key, $fieldArray)) {
                continue;
            }
            $newValue = !empty($value) ? $value : '';
            $oldValue = !empty($oldData[$key]) ? $oldData[$key] : '';

            if ($newValue != $oldValue) {
                $key = 'design/' . preg_replace('/_/', '/', $key, 1);
                $logData[$key] = [
                    'old_value' => $oldValue,
                    'new_value' => $newValue
                ];
            }
        }

        return $logData;
    }
}

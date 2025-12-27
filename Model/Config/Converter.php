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

namespace MageOS\AdminActivityLog\Model\Config;

use DOMDocument;
use DOMNode;
use DOMXPath;
use Magento\Framework\Config\ConverterInterface;

/**
 * Class Converter
 * @package MageOS\AdminActivityLog\Model\Config
 */
class Converter implements ConverterInterface
{
    /**
     * Convert actions in array from Xpath object
     * @param DOMDocument $source
     * @return array
     */
    public function convert($source)
    {
        $result = ['config' => []];
        $xpath = new DOMXPath($source);
        $result['config']['actions'] = $this->getActions($xpath);
        $result['config']['skip_edit_fields'] = $this->getGlobalSkipEditFields($xpath);

        $modules = $xpath->query('/config/modules/module');
        foreach ($modules as $module) {
            $moduleId = $module->attributes->getNamedItem('name')->nodeValue;
            $result['config'][$moduleId] = $this->processModule($module, $moduleId);
        }

        return $result;
    }

    /**
     * Retrieves global skip_edit_fields from Xpath object
     */
    protected function getGlobalSkipEditFields(DOMXPath $xpath): array
    {
        $result = [];
        $fields = $xpath->query('/config/skip_edit_fields/field');

        foreach ($fields as $field) {
            $result[] = trim($field->nodeValue);
        }

        return $result;
    }

    /**
     * Retrieves actions array from Xpath object
     * @param DOMXPath $xpath
     * @return array
     */
    protected function getActions($xpath): array
    {
        $result = [];
        $actions = $xpath->query('/config/actions/action');

        /** @var DOMNode $action */
        foreach ($actions as $action) {
            $actionId = $action->attributes->getNamedItem('id')->nodeValue;
            foreach ($action->childNodes as $label) {
                if ($label->nodeName === 'label') {
                    $result[$actionId] = $label->nodeValue;
                }
            }
        }
        return $result;
    }

    /**
     * Convert module node to array
     *
     * @param $module
     * @param $moduleId
     * @return array
     */
    protected function processModule($module, $moduleId): array
    {
        $result = [];
        foreach ($module->childNodes as $params) {
            switch ($params->nodeName) {
                case 'label':
                    $result['label'] = $params->nodeValue;
                    break;
                case 'models':
                    $result['model'] = $this->processModels($params);
                    break;
                case 'events':
                    $result['actions'] = $this->processEvents($params, $moduleId);
                    break;
                case 'config':
                    $result['config'] = $this->processConfig($params);
                    break;
            }
        }
        return $result;
    }

    /**
     * @param $events
     * @return array
     */
    protected function processModels($events): array
    {
        $result = [];
        foreach ($events->childNodes as $event) {
            if ($event->nodeName === 'class') {
                $result[] = $event->attributes->getNamedItem('name')->nodeValue;
            }
        }
        return $result;
    }

    /**
     * Convert events node to array
     *
     * @param $events
     * @param $moduleId
     * @return array
     */
    protected function processEvents($events, $moduleId): array
    {
        $result = [];
        foreach ($events->childNodes as $event) {
            if ($event->nodeName === 'event') {
                $result[$event->attributes->getNamedItem('controller_action')->nodeValue] = [
                    'action' => $event->attributes->getNamedItem('action_alias')->nodeValue,
                    'module' => $moduleId
                ];
                $postDispatch = $event->attributes->getNamedItem('post_dispatch');
                if ($postDispatch !== null) {
                    $result[$event->attributes->getNamedItem(
                        'controller_action'
                    )->nodeValue]['post_dispatch'] = $postDispatch->nodeValue;
                }
            }
        }
        return $result;
    }

    /**
     * Converts config to array
     *
     * @param $configs
     * @return array
     */
    protected function processConfig($configs): array
    {
        $result = [];
        foreach ($configs->childNodes as $config) {
            switch ($config->nodeName) {
                case 'trackfield':
                    $result['trackfield'] = $config->attributes->getNamedItem('method')->nodeValue;
                    break;
                case 'skip_fields':
                    $result['skip_fields'] = $this->processSkipFields($config);
                    break;
                case 'configpath':
                    $result['configpath'] = $config->attributes->getNamedItem('constant')->nodeValue;
                    break;
                case 'editurl':
                    $result['editurl'] = $config->attributes->getNamedItem('url')->nodeValue;
                    break;
                case 'itemfield':
                    $result['itemfield'] = $config->attributes->getNamedItem('field')->nodeValue;
                    break;
            }
        }
        return $result;
    }

    /**
     * Converts skip_fields to array
     */
    protected function processSkipFields(DOMNode $skipFieldsNode): array
    {
        $result = [];
        foreach ($skipFieldsNode->childNodes as $field) {
            if ($field->nodeName === 'field') {
                $result[] = trim($field->nodeValue);
            }
        }
        return $result;
    }
}

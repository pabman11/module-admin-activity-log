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

use Magento\Framework\Phrase;
use MageOS\AdminActivityLog\Model\Config\Data;

/**
 * Class Config
 * @package MageOS\AdminActivityLog\Model
 */
class Config
{
    /**
     * Merged adminactivity.xml config
     * @var array
     */
    private $xmlConfig;

    /**
     * Translated and sorted labels
     */
    private array $labels = [];

    /**
     * Config constructor.
     * @param Config\Data $dataStorage
     */
    public function __construct(
        Data $dataStorage
    ) {
        $this->xmlConfig = $dataStorage->get('config');
    }

    /**
     * Get all action labels translated and sorted ASC
     * @return array
     */
    public function getActions(): array
    {
        if (!$this->labels && isset($this->xmlConfig['actions'])) {
            foreach ($this->xmlConfig['actions'] as $id => $label) {
                $this->labels[$id] = __($label);
            }
            asort($this->labels);
        }
        return $this->labels;
    }

    /**
     * List of all full actions
     * @return array
     */
    public function getControllerActions(): array
    {
        $actions = [];
        foreach ($this->xmlConfig as $module => $config) {
            if (isset($config['actions'])) {
                $actions = array_merge($actions, array_keys($config['actions']));
            }
        }
        return $actions;
    }

    /**
     * Get logging action translated label
     * @param string $action
     * @return Phrase|string
     */
    public function getActionLabel(string $action)
    {
        if (isset($this->xmlConfig['actions'])
            && array_key_exists(
                $action,
                $this->xmlConfig['actions']
            )
        ) {
            return __($this->xmlConfig['actions'][$action]);
        }

        return $action;
    }

    /**
     * Get event by action
     * @param string $action
     * @return mixed
     */
    public function getEventByAction(string $action)
    {
        foreach ($this->xmlConfig as $module => $config) {
            if (isset($config['actions']) && array_key_exists($action, $config['actions'])) {
                return $config['actions'][$action];
            }
        }

        return null;
    }

    /**
     * Return Model class name
     * @param string $module
     * @return mixed
     */
    public function getEventModel(string $module)
    {
        if (!array_key_exists($module, $this->xmlConfig)) {
            return false;
        }
        return $this->xmlConfig[$module]['model'];
    }

    /**
     * Return model label name
     * @param string $module
     * @return mixed
     */
    public function getActivityModuleName(string $module)
    {
        if (!array_key_exists($module, $this->xmlConfig)) {
            return false;
        }

        return $this->xmlConfig[$module]['label'];
    }

    /**
     * Return skip fields for a module
     *
     * Returns either:
     * - array of skip fields (from XML skip_fields element)
     * - string method name (legacy, from trackfield attribute)
     * - false if module not found
     *
     * @param string $module
     * @return string[]|string|false
     */
    public function getTrackFieldModel(string $module): array|string|false
    {
        if (!array_key_exists($module, $this->xmlConfig)) {
            return false;
        }

        // Prefer skip_fields from XML over trackfield method
        $skipFields = $this->xmlConfig[$module]['config']['skip_fields'] ?? null;
        if ($skipFields !== null) {
            return $skipFields;
        }

        // Fall back to legacy trackfield method name
        return $this->xmlConfig[$module]['config']['trackfield'] ?? false;
    }

    /**
     * Return global skip edit fields from XML config
     * @return string[]
     */
    public function getGlobalSkipEditFields(): array
    {
        return $this->xmlConfig['skip_edit_fields'] ?? [];
    }

    /**
     * Return module constant
     * @param string $module
     * @return mixed
     */
    public function getActivityModuleConstant(string $module)
    {
        if (!array_key_exists($module, $this->xmlConfig)) {
            return false;
        }
        return $this->xmlConfig[$module]['config']['configpath'];
    }

    /**
     * Return module edit url
     * @param string $module
     * @return mixed
     */
    public function getActivityModuleEditUrl(string $module)
    {
        if (!array_key_exists($module, $this->xmlConfig)) {
            return false;
        }
        return $this->xmlConfig[$module]['config']['editurl'];
    }

    /**
     * Return module item name
     * @param string $module
     * @return mixed
     */
    public function getActivityModuleItemField(string $module)
    {
        if (!array_key_exists($module, $this->xmlConfig)) {
            return false;
        }
        return $this->xmlConfig[$module]['config']['itemfield'];
    }
}

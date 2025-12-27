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

use InvalidArgumentException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\ObjectManagerInterface;
use MageOS\AdminActivityLog\Api\ModelResolverInterface;

/**
 * Model resolver for dynamic model loading
 *
 * This class encapsulates ObjectManager usage for dynamic model resolution,
 * providing a testable abstraction for loading models by class name.
 *
 * ObjectManager usage is acceptable here as this is a factory/resolver class
 * that handles dynamic instantiation requirements.
 */
class ModelResolver implements ModelResolverInterface
{
    public function __construct(
        private readonly ObjectManagerInterface $objectManager
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getModel(string $className): AbstractModel
    {
        if (!$this->isValidModelClass($className)) {
            throw new InvalidArgumentException(
                sprintf('Class "%s" is not a valid AbstractModel', $className)
            );
        }

        /** @var AbstractModel $model */
        $model = $this->objectManager->create($className);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function loadModel(string $className, int|string $entityId, ?string $field = null): AbstractModel
    {
        $model = $this->getModel($className);

        if ($field !== null) {
            $model->load($entityId, $field);
        } else {
            $model->load($entityId);
        }

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function isValidModelClass(string $className): bool
    {
        if (!class_exists($className)) {
            return false;
        }

        return is_subclass_of($className, AbstractModel::class);
    }
}

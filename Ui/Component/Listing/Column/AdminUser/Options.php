<?php
/**
 * MageOS
 *
 * @category   MageOS
 * @package    MageOS_AdminActivityLog
 * @copyright  Copyright (C) 2025 MageOS (https://mage-os.org/)
 * @license    https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

declare(strict_types=1);

namespace MageOS\AdminActivityLog\Ui\Component\Listing\Column\AdminUser;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\User\Model\ResourceModel\User\CollectionFactory;

class Options implements OptionSourceInterface
{
    /**
     * @var array<int, array{value: int|string, label: string}>|null
     */
    private ?array $options = null;

    public function __construct(
        private readonly CollectionFactory $userCollectionFactory
    ) {
    }

    /**
     * Get admin users as options array
     *
     * @return array<int, array{value: int|string, label: string}>
     */
    public function toOptionArray(): array
    {
        if ($this->options === null) {
            $this->options = [];
            $collection = $this->userCollectionFactory->create();
            $collection->addFieldToSelect(['user_id', 'username', 'firstname', 'lastname']);

            foreach ($collection as $user) {
                $this->options[] = [
                    'value' => $user->getUserId(),
                    'label' => sprintf(
                        '%s %s (%s)',
                        $user->getFirstname(),
                        $user->getLastname(),
                        $user->getUsername()
                    )
                ];
            }
        }

        return $this->options;
    }
}

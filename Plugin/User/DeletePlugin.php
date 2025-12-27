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

namespace MageOS\AdminActivityLog\Plugin\User;

use Magento\Framework\Model\AbstractModel;
use Magento\User\Model\ResourceModel\User;
use MageOS\AdminActivityLog\Helper\Benchmark;

/**
 * Class Delete
 * @package MageOS\AdminActivityLog\Plugin\User
 */
class DeletePlugin
{
    public function __construct(
        private readonly Benchmark $benchmark
    ) {
    }

    public function aroundDelete(User $subject, callable $proceed, AbstractModel $user): bool
    {
        $this->benchmark->start(__METHOD__);
        $user->load($user->getId());

        /** @var bool $result */
        $result = $proceed($user);
        $user->afterDelete();

        $this->benchmark->end(__METHOD__);
        return $result;
    }
}

<?php
/**
 * MageOS
 *
 * @category   MageOS
 * @package    MageOS_AdminActivityLog
 * @copyright  Copyright (C) 2018 Kiwi Commerce Ltd (https://kiwicommerce.co.uk/)
 * @copyright  Copyright (C) 2024 MageOS (https://mage-os.org/)
 * @license    https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

declare(strict_types=1);

namespace MageOS\AdminActivityLog\Ui\Component\Listing\Column\ActionType;

use Magento\Framework\Option\ArrayInterface;
use MageOS\AdminActivityLog\Helper\Data;

/**
 * Class Options
 * @package MageOS\AdminActivityLog\Ui\Component\Listing\Column\ActionType
 */
class Options implements ArrayInterface
{
    public function __construct(
        private readonly Data $helper
    ) {
    }

    /**
     * List all option to get in filter
     * @return array
     */
    public function toOptionArray(): array
    {
        $data = [];
        $labelList = $this->helper->getAllActions();
        foreach ($labelList as $key => $value) {
            $data[] = ['value' => $key, 'label' => __($value)];
        }
        return $data;
    }
}

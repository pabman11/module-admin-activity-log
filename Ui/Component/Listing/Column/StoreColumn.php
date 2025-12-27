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

namespace MageOS\AdminActivityLog\Ui\Component\Listing\Column;

use Magento\Store\Ui\Component\Listing\Column\Store;

/**
 * Class StoreColumn
 * @package MageOS\AdminActivityLog\Ui\Component\Listing\Column
 */
class StoreColumn extends Store
{
    public const KEY_FIELD = 'store_id';

    /**
     * Prepare Item
     * @param array<string, mixed> $item
     */
    public function prepareItem(array $item): string
    {
        $this->storeKey = !empty($this->storeKey) ? $this->storeKey : self::KEY_FIELD;
        if (isset($item[$this->storeKey]) && (int)$item[$this->storeKey] === 0) {
            $origStores['0'] = 0;
        }

        if (!empty($item[$this->storeKey])) {
            $origStores = $item[$this->storeKey];
        }

        if (empty($origStores)) {
            return '';
        }
        if (!is_array($origStores)) {
            $origStores = [$origStores];
        }
        if (in_array(0, $origStores, true) && count($origStores) === 1) {
            return (string)__('All Store Views');
        }

        $data = $this->systemStore->getStoresStructure(false, $origStores);

        $content = '';
        foreach ($data as $website) {
            /** @var array{label: string, children: array<array{label: string, children: array<array{label: string}>}>} $website */
            $content .= $this->escaper->escapeHtml((string)$website['label']) . "<br/>";
            foreach ($website['children'] as $group) {
                $content .= str_repeat('&nbsp;', 3) . $this->escaper->escapeHtml((string)$group['label']) . "<br/>";
                foreach ($group['children'] as $store) {
                    $content .= str_repeat('&nbsp;', 6) . $this->escaper->escapeHtml((string)$store['label']) . "<br/>";
                }
            }
        }

        return $content;
    }
}

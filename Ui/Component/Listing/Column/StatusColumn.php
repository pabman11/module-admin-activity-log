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

namespace MageOS\AdminActivityLog\Ui\Component\Listing\Column;

use Magento\Framework\Escaper;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class StatusColumn
 * @package MageOS\AdminActivityLog\Ui\Component\Listing\Column
 */
class StatusColumn extends Column
{
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        private readonly Escaper $escaper,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     * @param array<string, mixed> $dataSource
     * @return array<string, mixed>
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items']) && is_array($dataSource['data']['items'])) {
            $name = $this->getData('name');
            $columnName = is_string($name) ? $name : '';
            foreach ($dataSource['data']['items'] as &$item) {
                if (!is_array($item)) {
                    continue;
                }
                if (!empty($item[$columnName])) {
                    $item[$columnName] = '<span class="grid-severity-notice"><span>Success</span></span>';
                } else {
                    $remark = $this->escaper->escapeHtmlAttr((string)($item['remarks'] ?? ''));
                    $item[$columnName] = '<span class="grid-severity-critical" title="' . $remark . '">';
                    $item[$columnName] .= '<span>Failed</span></span>';
                }
            }
        }

        return $dataSource;
    }
}

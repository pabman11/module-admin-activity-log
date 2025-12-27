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

namespace MageOS\AdminActivityLog\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Store\Model\Store;
use MageOS\AdminActivityLog\Api\ActivityRepositoryInterface;
use MageOS\AdminActivityLog\Api\FieldCheckerInterface;
use MageOS\AdminActivityLog\Helper\Browser;

/**
 * Class ActivityLogListing
 * @package MageOS\AdminActivityLog\Block\Adminhtml
 */
class ActivityLogListing extends Template
{
    protected $_template = 'MageOS_AdminActivityLog::log_listing.phtml';

    public function __construct(
        Context $context,
        protected readonly ActivityRepositoryInterface $activityRepository,
        protected readonly Browser $browser,
        protected readonly FieldCheckerInterface $protectedFieldChecker,
        array $data = [],
        ?JsonHelper $jsonHelper = null,
        ?DirectoryHelper $directoryHelper = null
    ) {
        parent::__construct(
            $context,
            $data,
            $jsonHelper,
            $directoryHelper
        );
    }

    /**
     * Get admin activity log listing
     *
     * @return null|array<int, array{
     *     entity_id: string,
     *     activity_id: string,
     *     field_name: string,
     *     old_value: string,
     *     new_value: string
     * }>
     */
    public function getLogListing(): ?array
    {
        $id = $this->getRequest()->getParam('id');
        if (!is_numeric($id)) {
            return null;
        }
        $data = $this->activityRepository->getActivityLog((int)$id);
        return $data->getData();
    }

    /**
     * Get admin activity details
     * @return array<string, string|null>
     * @throws NoSuchEntityException
     */
    public function getAdminDetails(): array
    {
        $id = $this->getRequest()->getParam('id');
        if (!is_numeric($id)) {
            return [];
        }
        $activity = $this->activityRepository->getActivityById((int)$id);

        $this->browser->reset();
        $this->browser->setUserAgent($activity->getUserAgent());
        $browser = $this->browser->__toString();

        $store = $this->_storeManager->getStore($activity->getStoreId());
        if ($store->getId() == 0) {
            $storeViewName = 'Default Config';
        } else {
            /** @var Store $store */
            $storeViewName = sprintf(
                '%s > %s > %s',
                $store->getWebsite()->getName(),
                $store->getGroup()->getName(),
                $store->getName()
            );
        }

        return [
            'username' => $activity->getUsername(),
            'module' => $activity->getModule(),
            'name' => $activity->getName(),
            'fullaction' => $activity->getFullaction(),
            'path' => $activity->getItemPath(),
            'scope' => $activity->getScope(),
            'store_name' => $storeViewName,
            'browser' => $browser,
            'date' => $activity->getUpdatedAt()
        ];
    }

    public function getProtectedFieldChecker(): FieldCheckerInterface
    {
        return $this->protectedFieldChecker;
    }
}

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

namespace MageOS\AdminActivityLog\Plugin\User;

use Magento\User\Model\ResourceModel\User;
use MageOS\AdminActivityLog\Helper\Benchmark;

/**
 * Class Delete
 * @package MageOS\AdminActivityLog\Plugin\User
 */
class Delete
{
    /**
     * @var Benchmark
     */
    public $benchmark;

    /**
     * Delete constructor.
     * @param Benchmark $benchmark
     */
    public function __construct(
        Benchmark $benchmark
    ) {
        $this->benchmark = $benchmark;
    }

    /**
     * @param User $user
     * @param callable $proceed
     * @param $object
     * @return mixed
     */
    public function aroundDelete(User $user, callable $proceed, $object)
    {
        $this->benchmark->start(__METHOD__);
        $object->load($object->getId());

        $result = $proceed($object);
        $object->afterDelete();

        $this->benchmark->end(__METHOD__);
        return $result;
    }
}

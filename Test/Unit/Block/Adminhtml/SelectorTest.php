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

namespace MageOS\AdminActivityLog\Test\Unit\Block\Adminhtml;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\UrlInterface;
use MageOS\AdminActivityLog\Block\Adminhtml\Selector;
use PHPUnit\Framework\TestCase;

/**
 * Class SelectorTest
 * @package MageOS\AdminActivityLog\Test\Unit\Block\Adminhtml
 */
class SelectorTest extends TestCase
{
    public $urlBuiler;

    public $revertUrl = 'http://magento.com/adminactivity/activity/revert';

    public $selector;

    /**
     * @requires PHP 7.0
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->urlBuiler = $this->createMock(UrlInterface::class);

        $this->selector = $objectManager->getObject(
            Selector::class,
            [
                '_urlBuilder' => $this->urlBuiler,
            ]
        );
    }

    /**
     * @requires PHP 7.0
     */
    public function testGetRevertUrl()
    {
        $this->urlBuiler->expects($this->once())
            ->method('getUrl')
            ->with('adminactivity/activity/revert')
            ->willReturn($this->revertUrl);

        $this->assertEquals($this->revertUrl, $this->selector->getRevertUrl());
    }
}

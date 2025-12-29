<?php
declare(strict_types=1);
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

namespace MageOS\AdminActivityLog\Model;

use Magento\Framework\Model\AbstractModel;
use MageOS\AdminActivityLog\Api\Data\LoginInterface;

class Login extends AbstractModel implements LoginInterface
{
    public const LOGIN_ACTIVITY_ID = 'entity_id';

    public function _construct(): void
    {
        $this->_init(ResourceModel\Login::class);
    }

    public function getAdminId(): ?int
    {
        $value = $this->getData(self::ADMIN_ID);
        return $value === null ? null : (int)$value;
    }

    public function setAdminId(?int $adminId): LoginInterface
    {
        return $this->setData(self::ADMIN_ID, $adminId);
    }

    public function getUsername(): string
    {
        return (string)$this->getData(self::USERNAME);
    }

    public function setUsername(string $username): LoginInterface
    {
        return $this->setData(self::USERNAME, $username);
    }

    public function getName(): string
    {
        return (string)$this->getData(self::NAME);
    }

    public function setName(string $name): LoginInterface
    {
        return $this->setData(self::NAME, $name);
    }

    public function getRemoteIp(): string
    {
        return (string)$this->getData(self::REMOTE_IP);
    }

    public function setRemoteIp(string $remoteIp): LoginInterface
    {
        return $this->setData(self::REMOTE_IP, $remoteIp);
    }

    public function getForwardedIp(): ?string
    {
        $value = $this->getData(self::FORWARDED_IP);
        return $value === null ? null : (string)$value;
    }

    public function setForwardedIp(?string $forwardedIp): LoginInterface
    {
        return $this->setData(self::FORWARDED_IP, $forwardedIp);
    }

    public function getUserAgent(): string
    {
        return (string)$this->getData(self::USER_AGENT);
    }

    public function setUserAgent(string $userAgent): LoginInterface
    {
        return $this->setData(self::USER_AGENT, $userAgent);
    }

    public function getType(): string
    {
        return (string)$this->getData(self::TYPE);
    }

    public function setType(string $type): LoginInterface
    {
        return $this->setData(self::TYPE, $type);
    }

    public function getStatus(): ?bool
    {
        $value = $this->getData(self::STATUS);
        return $value === null ? null : (bool)$value;
    }

    public function setStatus(?bool $status): LoginInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    public function getRemarks(): ?string
    {
        $value = $this->getData(self::REMARKS);
        return $value === null ? null : (string)$value;
    }

    public function setRemarks(?string $remarks): LoginInterface
    {
        return $this->setData(self::REMARKS, $remarks);
    }

    public function getCreatedAt(): string
    {
        return (string)$this->getData(self::CREATED_AT);
    }

    public function setCreatedAt(string $createdAt): LoginInterface
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }
}

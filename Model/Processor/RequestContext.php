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

namespace MageOS\AdminActivityLog\Model\Processor;

use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;

/**
 * Context object aggregating request-related dependencies for Processor
 *
 * This class reduces constructor parameters in the Processor class by
 * grouping request-related services together.
 */
class RequestContext
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly Http $httpRequest,
        private readonly RemoteAddress $remoteAddress
    ) {
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function getHttpRequest(): Http
    {
        return $this->httpRequest;
    }

    public function getRemoteAddress(): RemoteAddress
    {
        return $this->remoteAddress;
    }

    /**
     * Get forwarded IP from X-Forwarded-For header
     */
    public function getForwardedIp(): ?string
    {
        return $this->httpRequest->getServer('HTTP_X_FORWARDED_FOR');
    }
}

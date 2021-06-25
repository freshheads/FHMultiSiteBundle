<?php

declare(strict_types=1);

namespace FH\Bundle\MultiSiteBundle\Site;

use Symfony\Component\Routing\RequestContext;

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
final class SiteNotFoundException extends \RuntimeException
{
    public static function withRequestContext(RequestContext $requestContext): self
    {
        return new self(sprintf('No site found for hostname %s and path %s', $requestContext->getHost(), $requestContext->getPathInfo()));
    }
}

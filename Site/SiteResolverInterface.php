<?php

declare(strict_types=1);

namespace FH\Bundle\MultiSiteBundle\Site;

use Symfony\Component\Routing\RequestContext;

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
interface SiteResolverInterface
{
    /**
     * @throws SiteNotFoundException
     */
    public function resolve(RequestContext $requestContext): SiteInterface;
}

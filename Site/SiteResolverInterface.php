<?php
declare(strict_types=1);

namespace FH\Bundle\MultiSiteBundle\Site;

use Symfony\Component\Routing\RequestContext;
use FH\Bundle\MultiSiteBundle\Site\SiteInterface;

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
interface SiteResolverInterface
{
    /**
     * @param RequestContext $requestContext
     * @return SiteInterface
     *
     * @throws SiteNotFoundException
     */
    public function resolve(RequestContext $requestContext): SiteInterface;
}

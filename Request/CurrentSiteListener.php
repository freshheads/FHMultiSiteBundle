<?php

declare(strict_types=1);

namespace FH\Bundle\MultiSiteBundle\Request;

use FH\Bundle\MultiSiteBundle\Site\SiteResolverInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\RequestContext;

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
final class CurrentSiteListener
{
    private $siteResolver;

    public function __construct(SiteResolverInterface $siteResolver)
    {
        $this->siteResolver = $siteResolver;
    }

    public function __invoke(RequestEvent $event): void
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();
        $requestContext = new RequestContext();
        $requestContext->fromRequest($request);

        $site = $this->siteResolver->resolve($requestContext);

        $request->attributes->set('site', $site);
    }
}

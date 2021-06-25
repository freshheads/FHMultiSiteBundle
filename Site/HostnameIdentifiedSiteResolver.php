<?php

declare(strict_types=1);

namespace FH\Bundle\MultiSiteBundle\Site;

use Symfony\Component\Routing\RequestContext;

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
final class HostnameIdentifiedSiteResolver implements SiteResolverInterface
{
    private $siteRepository;
    private $identifierMapping;

    public function __construct(SiteRepositoryInterface $siteRepository, IdentifierMappingInterface $identifierMapping)
    {
        $this->siteRepository = $siteRepository;
        $this->identifierMapping = $identifierMapping;
    }

    /**
     * @throws SiteNotFoundException
     */
    public function resolve(RequestContext $requestContext): SiteInterface
    {
        $identifier = $this->identifierMapping->findIdentifierByHostname($requestContext->getHost());

        if (null === $identifier) {
            throw SiteNotFoundException::withRequestContext($requestContext);
        }

        foreach ($this->siteRepository->findAll() as $site) {
            if ($site instanceof IdentifiedSiteInterface && $site->matches($identifier)) {
                return $site;
            }
        }

        throw SiteNotFoundException::withRequestContext($requestContext);
    }
}

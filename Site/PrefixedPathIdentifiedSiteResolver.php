<?php

declare(strict_types=1);

namespace FH\Bundle\MultiSiteBundle\Site;

use Symfony\Component\Routing\RequestContext;

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
final class PrefixedPathIdentifiedSiteResolver implements SiteResolverInterface
{
    private $siteRepository;
    private $defaultIdentifier;
    private $identifiers;

    /**
     * @param string[] $identifiers
     * @param string   $defaultIdentifier
     */
    public function __construct(SiteRepositoryInterface $siteRepository, array $identifiers, string $defaultIdentifier = null)
    {
        $this->siteRepository = $siteRepository;
        $this->defaultIdentifier = $defaultIdentifier;
        $this->identifiers = $identifiers;
    }

    public function resolve(RequestContext $requestContext): SiteInterface
    {
        $matches = [];

        if (preg_match('#^/([^/]+)/?#', $requestContext->getPathInfo(), $matches) > 0) {
            $identifier = $matches[1];
        } else {
            $identifier = $this->defaultIdentifier;
        }

        if (empty($identifier)) {
            throw SiteNotFoundException::withRequestContext($requestContext);
        }

        foreach ($this->siteRepository->findAll() as $site) {
            if (!$site instanceof IdentifiedSiteInterface) {
                continue;
            }

            if ($site->matches($identifier)) {
                return $site;
            }

            if ($site->matches($this->defaultIdentifier)) {
                $defaultSite = $site;
            }
        }

        if (!empty($defaultSite) && $defaultSite instanceof IdentifiedSiteInterface) {
            return $defaultSite;
        }

        throw SiteNotFoundException::withRequestContext($requestContext);
    }
}

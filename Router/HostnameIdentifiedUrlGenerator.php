<?php

declare(strict_types=1);

namespace FH\Bundle\MultiSiteBundle\Router;

use FH\Bundle\MultiSiteBundle\Site\IdentifiedSiteInterface;
use FH\Bundle\MultiSiteBundle\Site\IdentifierMappingInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
final class HostnameIdentifiedUrlGenerator implements UrlGeneratorInterface
{
    private $urlGenerator;
    private $requestContext;
    private $identifierMapping;
    private $requestStack;

    public function __construct(UrlGeneratorInterface $urlGenerator, IdentifierMappingInterface $identifierMapping, RequestStack $requestStack)
    {
        $this->urlGenerator = $urlGenerator;
        $this->identifierMapping = $identifierMapping;
        $this->requestStack = $requestStack;
    }

    public function setContext(RequestContext $context): void
    {
        $this->requestContext = $context;
    }

    public function getContext(): ?RequestContext
    {
        return $this->requestContext;
    }

    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH): string
    {
        $identifier = $this->resolveIdentifier($parameters);

        if (isset($identifier)) {
            $hostnames = $this->identifierMapping->findHostnamesByIdentifier($identifier);

            if (\count($hostnames) > 0) {
                $parameters['site_hostname'] = $hostnames[0];
            }

            unset($parameters['site']);
        }

        return $this->urlGenerator->generate($name, $parameters, $referenceType);
    }

    private function resolveIdentifier(array $parameters): ?string
    {
        if (isset($parameters['site'])) {
            if ($parameters['site'] instanceof IdentifiedSiteInterface) {
                /** @var IdentifiedSiteInterface $site */
                $site = $parameters['site'];

                return $site->getIdentifier();
            }

            if (\is_string($parameters['site'])) {
                return $parameters['site'];
            }
        }

        $masterRequest = $this->requestStack->getMasterRequest();

        if ($masterRequest instanceof Request) {
            $site = $masterRequest->attributes->get('site');

            if ($site instanceof IdentifiedSiteInterface) {
                return $site->getIdentifier();
            }
        }

        return null;
    }
}
